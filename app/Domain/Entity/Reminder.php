<?php declare(strict_types=1);

namespace app\Domain\Entity;

use app\Domain\ValueObject\ChatId;
use app\Domain\ValueObject\Status;
use app\Domain\ValueObject\UserId;

class Reminder
{
    private ?int $id = null;

    public function __construct(
        private ChatId $chatId,
        private UserId $creatorId,
        private UserId $targetUserId,
        private string $text,
        private \DateTimeImmutable $remindAt,
        private Status $status,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChatId(): ChatId
    {
        return $this->chatId;
    }

    public function getCreatorId(): UserId
    {
        return $this->creatorId;
    }

    public function getTargetUserId(): UserId
    {
        return $this->targetUserId;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getRemindAt(): \DateTimeImmutable
    {
        return $this->remindAt;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
}
