<?php declare(strict_types=1);

namespace app\Domain\ValueObject;

final class ChatId
{
    public function __construct(private int $value)
    {
        if (abs($value) < 1_000_000) {
            throw new \InvalidArgumentException("Chat ID looks suspicious: $value");
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(ChatId $other): bool
    {
        return $this->value === $other->value;
    }
}
