<?php declare(strict_types=1);

namespace App\Domain\ValueObject;

namespace app\Domain\ValueObject;

final class UserId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException("User ID must be positive.");
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(UserId $other): bool
    {
        return $this->value === $other->value;
    }
}
