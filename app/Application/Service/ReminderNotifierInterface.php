<?php declare(strict_types=1);

namespace App\Application\Service;

interface ReminderNotifierInterface
{
    public function notify(int $reminderId, int $chatId, string $message): void;
    public function removeCancelButton(int $chatId, int $messageId): void;
}
