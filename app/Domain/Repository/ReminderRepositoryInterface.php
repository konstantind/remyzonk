<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Reminder;

interface ReminderRepositoryInterface
{
    public function save(Reminder $reminder): void;

    public function saveMessageId(Reminder $reminder, int $messageId): void;

    public function complete(int $id): void;

    public function delete(int $id): void;

    public function findById(int $id): ?Reminder;

    /**
     * @return Reminder[]
     */
    public function findPending(\DateTimeImmutable $now): iterable;
}
