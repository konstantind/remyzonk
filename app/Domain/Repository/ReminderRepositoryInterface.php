<?php declare(strict_types=1);

namespace app\Domain\Repository;

use app\Domain\Entity\Reminder;

interface ReminderRepositoryInterface
{
    public function save(Reminder $reminder): void;

    /**
     * @return Reminder[]
     */
    public function findPending(\DateTimeImmutable $now): iterable;
}
