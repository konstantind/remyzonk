<?php declare(strict_types=1);

namespace App\Application\UseCase\CreateReminderUseCase;

use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\UserId;

readonly class CreateReminderRequestDTO
{
    public function __construct(
        public ChatId             $chatId,
        public UserId             $creatorId,
        public ?UserId            $targetUserId,
        public string             $text,
        public \DateTimeImmutable $remindAt,
    )
    {
    }
}
