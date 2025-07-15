<?php declare(strict_types=1);

namespace app\Domain\ValueObject;

enum Status: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case DONE = 'done';

    public static function fromString(string $value): self
    {
        $status = self::tryFrom($value);
        if ($status === null) {
            throw new \InvalidArgumentException("Invalid status: $value");
        }
        return $status;
    }
}
