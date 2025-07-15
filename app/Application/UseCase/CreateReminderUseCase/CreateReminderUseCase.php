<?php declare(strict_types=1);

namespace App\Application\UseCase\CreateReminderUseCase;

use app\Domain\Entity\Reminder;
use app\Domain\Repository\ReminderRepositoryInterface;
use app\Domain\ValueObject\Status;

class CreateReminderUseCase
{
    public function __construct(
        private ReminderRepositoryInterface $repository
    )
    {
    }

    public function execute(CreateReminderRequestDTO $dto): CreateReminderResponseDTO
    {
        $reminder = new Reminder(
            chatId: $dto->chatId,
            creatorId: $dto->creatorId,
            targetUserId: $dto->targetUserId ?? $dto->creatorId,
            text: $dto->text,
            remindAt: $dto->remindAt,
            status: Status::pending(),
        );

        $this->repository->save($reminder);

        return CreateReminderResponseDTO::fromEntity($reminder);
    }
}

