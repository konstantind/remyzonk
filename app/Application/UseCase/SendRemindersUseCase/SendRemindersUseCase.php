<?php declare(strict_types=1);

namespace App\Application\UseCase\SendRemindersUseCase;

use App\Application\Service\ReminderNotifierInterface;
use App\Domain\Repository\ReminderRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Status;
use Illuminate\Support\Facades\Log;

class SendRemindersUseCase
{
    public function __construct(
        private ReminderRepositoryInterface $reminderRepository,
        private ReminderNotifierInterface   $notifier,
        private UserRepositoryInterface     $userRepository,
    )
    {
    }

    public function execute(\DateTimeImmutable $now): void
    {
        $pendingReminders = $this->reminderRepository->findPending($now);

        foreach ($pendingReminders as $reminder) {
            $targetUserId = $reminder->getTargetUserId();

            if ($targetUserId !== null) {
                $targetUser = $this->userRepository->findById($targetUserId->value());

                $privateChatId = $targetUser->getPrivateChatId()->value();
                Log::info("privateChatId = $privateChatId");

                if ($targetUser && $targetUser->getPrivateChatId()) {
                    $this->notifier->notify(
                        $targetUser->getPrivateChatId()->value(),
                        "Напоминание: " . $reminder->getText(),
                    );
                }
            }

            $this->notifier->notify(
                $reminder->getChatId()->value(),
                "Напоминание: " . $reminder->getText(),
            );

            $reminder->setStatus(Status::DONE);
            $this->reminderRepository->save($reminder);
        }
    }
}
