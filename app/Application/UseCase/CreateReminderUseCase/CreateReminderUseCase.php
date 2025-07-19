<?php declare(strict_types=1);

namespace App\Application\UseCase\CreateReminderUseCase;

use App\Domain\Entity\Reminder;
use App\Domain\Repository\ReminderRepositoryInterface;
use App\Domain\ValueObject\Status;

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
            status: Status::PENDING,
        );

        $this->repository->save($reminder);

        return CreateReminderResponseDTO::fromEntity($reminder);
    }
}

