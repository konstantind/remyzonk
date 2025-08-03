<?php declare(strict_types=1);

namespace App\Application\UseCase\CompleteReminderUseCase;

use App\Domain\Repository\ReminderRepositoryInterface;

class CompleteReminderUseCase
{
    public function __construct(
        private ReminderRepositoryInterface $repository
    )
    {
    }

    public function execute(CompleteReminderRequestDTO $dto): void
    {
        $this->repository->complete($dto->reminderId);
    }
}
