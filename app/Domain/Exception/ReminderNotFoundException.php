<?php declare(strict_types=1);

namespace App\Domain\Exception;

class ReminderNotFoundException extends \RuntimeException
{
    public static function withId(int $id): self
    {
        return new self("Reminder not found with ID: {$id}");
    }
}
