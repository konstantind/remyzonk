<?php declare(strict_types = 1);

namespace app\Infrastructure\Service;

use App\Application\Service\ReminderNotifierInterface;
use Telegram\Bot\Api;

class TelegramReminderNotifier implements ReminderNotifierInterface
{
    public function __construct(private Api $telegram) {}

    public function notify(int $chatId, string $message): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }
}
