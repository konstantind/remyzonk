<?php declare(strict_types=1);

namespace App\Infrastructure\Telegram\Commands;

use App\Application\UseCase\DeleteReminderUseCase\DeleteReminderRequestDTO;
use App\Application\UseCase\DeleteReminderUseCase\DeleteReminderUseCase;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Api;

class DeleteReminderCallbackCommand extends Command
{
    protected string $name = 'callback_query';
    protected string $description = 'Handles inline keyboard callbacks';

    public function __construct(
        private DeleteReminderUseCase $deleteReminderUseCase,
    )
    {
    }

    public function make(Api $telegram, Update $update, array $entity = []): mixed
    {
        $this->telegram = $telegram;
        $this->run($update);
        return $this;
    }

    public function handle(): void
    {
    }

    public function run(Update $update): void
    {
        $callbackQuery = $update->getCallbackQuery();
        $data = $callbackQuery->getData();
        $chatId = $callbackQuery->getMessage()->getChat()->getId();

        if (preg_match('/^delete_reminder:(\d+)$/', $data, $matches)) {
            $reminderId = (int)$matches[1];

            try {
                $this->deleteReminderUseCase->execute(new DeleteReminderRequestDTO($reminderId));

                $this->telegram->editMessageText([
                    'chat_id' => $chatId,
                    'message_id' => $callbackQuery->getMessage()->getMessageId(),
                    'text' => $callbackQuery->getMessage()->getText() . "\n❌ Отменено",
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode(['inline_keyboard' => []]),
                ]);
            } catch (\Throwable $e) {
                $this->telegram->answerCallbackQuery([
                    'callback_query_id' => $callbackQuery->getId(),
                    'text' => "Не удалось отменить напоминание",
                    'show_alert' => true,
                ]);
            }
        }
    }
}
