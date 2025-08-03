<?php declare(strict_types=1);

namespace App\Application\UseCase\SendRemindersUseCase;

use App\Application\Service\ReminderNotifierInterface;
use App\Domain\Repository\ReminderRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;

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
            $chatId = $reminder->getChatId()->value();
            $text = "Напоминание: " . $reminder->getText();

            $sentToPrivate = false;

            if ($targetUserId !== null) {
                $targetUser = $this->userRepository->findById($targetUserId->value());

                if ($targetUser && $targetUser->getPrivateChatId()) {
                    $privateChatId = $targetUser->getPrivateChatId()->value();

                    if ($privateChatId === $chatId) {
                        $this->notifier->notify($reminder->getId(), $chatId, $text);
                        $sentToPrivate = true;
                    } else {
                        $this->notifier->notify($reminder->getId(), $privateChatId, $text);
                    }
                }
            }

            if (!$sentToPrivate) {
                $this->notifier->notify($reminder->getId(), $chatId, $text);
            }

            if ($reminder->getMessageId()) {
                $this->notifier->removeCancelButton($chatId, $reminder->getMessageId());
            }
        }
    }
}
