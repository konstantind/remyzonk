<?php declare(strict_types=1);

namespace App\Application\UseCase\DeleteReminderUseCase;

use App\Domain\Repository\ReminderRepositoryInterface;

class DeleteReminderUseCase
{
    public function __construct(
        private ReminderRepositoryInterface $repository
    )
    {
    }

    public function execute(DeleteReminderRequestDTO $dto): void
    {
        $this->repository->delete($dto->reminderId);
    }
}
