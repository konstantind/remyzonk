<?php declare(strict_types=1);

namespace App\Application\UseCase\CompleteReminderUseCase;

readonly class CompleteReminderRequestDTO
{
    public function __construct(
        public int $reminderId
    )
    {
    }
}
