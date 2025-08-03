<?php declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\UserId;

final class User
{
    public function __construct(
        private UserId $id,
        private string $username,
        private ?ChatId $privateChatId,
    ) {}


    public function getUsername(): string
    {
        return $this->username;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getPrivateChatId(): ?ChatId
    {
        return $this->privateChatId;
    }
}
