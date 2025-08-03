<?php declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Service\ReminderNotifierInterface;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramReminderNotifier implements ReminderNotifierInterface
{
    public function __construct(private Api $telegram)
    {
    }

    public function notify(int $reminderId, int $chatId, string $message): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => "🔔 {$message}",
            'reply_markup' => json_encode([
                'inline_keyboard' => [
//                    [
//                        ['text' => '🕒 +15 мин', 'callback_data' => "reschedule_reminder:15"],
//                        ['text' => '🕐 +1 час', 'callback_data' => "reschedule_reminder:60"],
//                    ],
                    [
                        [
                            'text' => '✅ Завершить',
                            'callback_data' => "complete_reminder:" . $reminderId
                        ],
                    ],
                ],
            ]),
        ]);
    }

    public function removeCancelButton(int $chatId, int $messageId): void
    {
        try {
            $this->telegram->editMessageReplyMarkup([
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'reply_markup' => json_encode(['inline_keyboard' => []])
            ]);
        } catch (TelegramSDKException $e) {
            Log::warning("Failed to remove inline keyboard for message $messageId in chat $chatId: " . $e->getMessage());
        }
    }
}
