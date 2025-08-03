<?php declare(strict_types=1);

namespace App\Infrastructure\Telegram\Commands;

use App\Application\Service\ReminderDateTimeParserInterface;
use App\Application\UseCase\CreateReminderUseCase\CreateReminderRequestDTO;
use App\Application\UseCase\CreateReminderUseCase\CreateReminderUseCase;
use App\Application\UseCase\SaveUserUseCase\SaveUserUseCase;
use App\Application\UseCase\SaveUserUseCase\SaveUserUseCaseRequestDTO;
use App\Application\UseCase\SetReminderMessageIdUseCase\SetReminderMessageIdUseCase;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\UserId;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Commands\Command;

class RemindCommand extends Command
{
    protected string $name = 'remind';
    protected string $description = 'Создать напоминание. Пример: /remind 15:30 Позвонить родителям';

    public function __construct(
        protected ReminderDateTimeParserInterface $parser,
        protected UserRepositoryInterface $userRepository,
        protected CreateReminderUseCase $createReminderUseCase,
        protected SetReminderMessageIdUseCase $setReminderMessageIdUseCase,
        protected SaveUserUseCase $saveUserUseCase,
    ) {
    }

    public function handle()
    {
        $message = $this->getUpdate()->getMessage();

        $entities = $message->getEntities();
        $chatId = $message->getChat()->getId();
        $fromUser = $message->getFrom();
        $text = $message->getText();
        $chat = $message->getChat();

        $privateChatId = null;

        if ($chat->getType() === 'private') {
            $privateChatId = $chat->getId();
        }

        $this->saveUserUseCase->execute(
            new SaveUserUseCaseRequestDTO(
                $fromUser->getId(),
                $fromUser->getUsername(),
                $privateChatId,
            )
        );

        $targetUserId = null;

        if ($entities) {
            foreach ($entities as $entity) {
                if ($entity->getType() === 'mention') {
                    $offset = $entity->getOffset();
                    $length = $entity->getLength();
                    $mention = mb_substr($text, $offset, $length);
                    $username = ltrim($mention, '@');

                    $targetUser = $this->userRepository->findByUsername($username);

                    if ($targetUser) {
                        $targetUserId = new UserId($targetUser->getId()->value());
                    }
                }
            }
        }

        $textWithoutCommand = trim(mb_substr($text, mb_strlen('/remind')));

        $now = new \DateTimeImmutable();

        try {
            $remindAt = $this->parser->parse($textWithoutCommand, $now);
        } catch (\InvalidArgumentException $e) {
            $this->replyWithMessage([
                'text' => "❌ Не удалось распознать дату/время. Пример: /remind 15:30 Позвонить родителям",
            ]);
            return;
        }

        $dto = new CreateReminderRequestDTO(
            chatId: new ChatId($chatId),
            creatorId: new UserId($fromUser->getId()),
            targetUserId: $targetUserId,
            text: $textWithoutCommand,
            remindAt: $remindAt,
        );

        $reminderResponseDTO = $this->createReminderUseCase->execute($dto);

        $response = $this->replyWithMessage([
            'text' => "🆕 Напоминание сохранено на " . $remindAt->format('Y-m-d H:i') . ":\n" . $textWithoutCommand,
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [
                        [
                            'text' => '❌ Отменить напоминание',
                            'callback_data' => 'delete_reminder:' . $reminderResponseDTO->id,
                        ]
                    ]
                ]
            ])
        ]);

        $messageId = $response->getMessageId();
        $this->setReminderMessageIdUseCase->execute($reminderResponseDTO->id, $messageId);
    }
}
