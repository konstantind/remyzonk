<?php declare(strict_types=1);

namespace app\Application\UseCase\CreateReminderUseCase;

use app\Domain\ValueObject\ChatId;
use app\Domain\ValueObject\UserId;

class CreateReminderRequestDTO
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
