<?php declare(strict_types=1);

namespace App\Domain\ValueObject;

final class ChatId
{
    private int $value;

    public function __construct(int $value)
    {
        $this->isValid($value);
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function isValid(int $value): void
    {
        if (abs($value) < 1_000_000) {
            throw new \InvalidArgumentException("Chat ID looks suspicious: $value");
        }
    }
}
