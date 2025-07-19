<?php declare(strict_types=1);

namespace App\Domain\ValueObject;

final class UserId
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
        if ($value <= 0) {
            throw new \InvalidArgumentException("User ID must be positive.");
        }
    }
}
