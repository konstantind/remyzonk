<?php declare(strict_types=1);

namespace app\Domain\ValueObject;

final class Status
{
    private function __construct(private string $value) {}

    public static function pending(): self { return new self('pending'); }
    public static function sent(): self { return new self('sent'); }
    public static function done(): self { return new self('done'); }

    public static function from(string $value): self
    {
        return match ($value) {
            'pending' => self::pending(),
            'sent'    => self::sent(),
            'done'    => self::done(),
            default   => throw new \InvalidArgumentException("Invalid status: $value"),
        };
    }

    public function equals(Status $other): bool
    {
        return $this->value === $other->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}

