<?php declare(strict_types=1);

namespace app\Application\UseCase\CreateReminderUseCase;

use app\Domain\Entity\Reminder;

readonly class CreateReminderResponseDTO
{
    public function __construct(
        public int                $id,
        public string             $text,
        public \DateTimeImmutable $remindAt,
        public int                $chatId,
        public int                $creatorId,
        public int                $targetUserId,
        public string             $status,
    )
    {
    }

    public static function fromEntity(Reminder $reminder): self
    {
        return new self(
            id: $reminder->getId(),
            text: $reminder->getText(),
            remindAt: $reminder->getRemindAt(),
            chatId: $reminder->getChatId()->value(),
            creatorId: $reminder->getCreatorId()->value(),
            targetUserId: $reminder->getTargetUserId()->value(),
            status: $reminder->getStatus()->value,
        );
    }
}
