<?php declare(strict_types=1);

namespace app\Application\UseCase\SendRemindersUseCase;

use App\Application\Service\ReminderNotifierInterface;
use app\Domain\Repository\ReminderRepositoryInterface;
use app\Domain\ValueObject\Status;

class SendRemindersUseCase
{
    public function __construct(
        private ReminderRepositoryInterface $reminderRepository,
        private ReminderNotifierInterface   $notifier,
    )
    {
    }

    public function execute(\DateTimeImmutable $now): void
    {
        $pendingReminders = $this->reminderRepository->findPending($now);

        foreach ($pendingReminders as $reminder) {
            $this->notifier->notify(
                $reminder->getChatId()->value(),
                "Напоминание: " . $reminder->getText(),
            );

            $reminder->setStatus(Status::DONE);
            $this->reminderRepository->save($reminder);
        }
    }
}
