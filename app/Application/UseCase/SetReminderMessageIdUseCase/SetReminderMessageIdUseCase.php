<?php declare(strict_types=1);

namespace App\Application\UseCase\SetReminderMessageIdUseCase;

use App\Domain\Exception\ReminderNotFoundException;
use App\Domain\Repository\ReminderRepositoryInterface;

class SetReminderMessageIdUseCase
{
    public function __construct(
        private ReminderRepositoryInterface $reminderRepository,
    )
    {
    }

    public function execute(int $reminderId, int $messageId)
    {
        $reminder = $this->reminderRepository->findById($reminderId);

        if (!$reminder) {
            throw ReminderNotFoundException::withId($reminderId);
        }

        $this->reminderRepository->saveMessageId($reminder, $messageId);
    }
}
