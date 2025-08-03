<?php declare(strict_types=1);

namespace App\Infrastructure\Telegram\Commands;

use App\Application\UseCase\CompleteReminderUseCase\CompleteReminderRequestDTO;
use App\Application\UseCase\CompleteReminderUseCase\CompleteReminderUseCase;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Api;

class CompleteReminderCallbackCommand extends Command
{
    protected string $name = 'callback_query';
    protected string $description = 'Handles inline keyboard callbacks';

    public function __construct(
        private CompleteReminderUseCase $completeReminderUseCase,
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

        if (preg_match('/^complete_reminder:(\d+)$/', $data, $matches)) {
            $reminderId = (int)$matches[1];

            try {
                $this->completeReminderUseCase->execute(new CompleteReminderRequestDTO($reminderId));

                $this->telegram->editMessageText([
                    'chat_id' => $chatId,
                    'message_id' => $callbackQuery->getMessage()->getMessageId(),
                    'text' => $callbackQuery->getMessage()->getText() . "\n✅ Завершено",
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode(['inline_keyboard' => []]),
                ]);
            } catch (\Throwable $e) {
                $this->telegram->answerCallbackQuery([
                    'callback_query_id' => $callbackQuery->getId(),
                    'text' => "Не удалось завершить напоминание",
                    'show_alert' => true,
                ]);
            }
        }
    }
}
