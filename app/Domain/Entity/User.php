<?php declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\UserId;

class User
{
    public function __construct(
        private UserId $id,
        private string $username,
    ) {}


    public function getUsername(): string
    {
        return $this->username;
    }

    public function getId(): UserId
    {
        return $this->id;
    }
}
