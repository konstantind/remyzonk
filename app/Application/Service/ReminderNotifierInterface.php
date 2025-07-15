<?php declare(strict_types=1);

namespace App\Application\Service;

interface ReminderNotifierInterface
{
    public function notify(int $chatId, string $message): void;
}
