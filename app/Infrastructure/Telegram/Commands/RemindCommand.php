<?php declare(strict_types=1);

namespace App\Infrastructure\Telegram\Commands;

use App\Application\Service\ReminderDateTimeParserInterface;
use App\Application\UseCase\CreateReminderUseCase\CreateReminderRequestDTO;
use App\Application\UseCase\CreateReminderUseCase\CreateReminderUseCase;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\UserId;
use Telegram\Bot\Commands\Command;

class RemindCommand extends Command
{
    protected string $name = 'remind';
    protected string $description = 'Создать напоминание. Пример: /remind 15:30 Позвонить родителям';

    public function __construct(
        protected ReminderDateTimeParserInterface $parser,
        protected UserRepositoryInterface $userRepository,
        protected CreateReminderUseCase $createReminderUseCase,
    ) {
    }

    public function handle()
    {
        $message = $this->getUpdate()->getMessage();

        $entities = $message->getEntities();
        $chatId = $message->getChat()->getId();
        $fromUser = $message->getFrom();
        $text = $message->getText();

        $user = $this->userRepository->findById($fromUser->getId());

        if (!$user) {
            $user = new User(
                id: new UserId($fromUser->getId()),
                username: $fromUser->getUsername(),
            );
            $this->userRepository->save($user);
        }

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

        $this->createReminderUseCase->execute($dto);

        $this->replyWithMessage([
            'text' => "✅ Напоминание сохранено на " . $remindAt->format('Y-m-d H:i') . ":\n" . $textWithoutCommand,
        ]);
    }
}
