<?php declare(strict_types=1);

namespace App\Application\UseCase\DeleteReminderUseCase;

readonly class DeleteReminderRequestDTO
{
    public function __construct(
        public int $reminderId
    )
    {
    }
}
