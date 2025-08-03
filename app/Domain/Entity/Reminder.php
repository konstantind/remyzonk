<?php declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\Status;
use App\Domain\ValueObject\UserId;

final class Reminder
{
    private ?int $id = null;
    private ?int $messageId = null;

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

    public function getMessageId(): ?int
    {
        return $this->messageId;
    }
}
