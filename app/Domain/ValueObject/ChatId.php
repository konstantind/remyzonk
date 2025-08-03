<?php declare(strict_types=1);

namespace App\Domain\ValueObject;

final class ChatId
{
    private const MIN_TELEGRAM_CHAT_ID = 1_000_000;
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
        if (abs($value) < self::MIN_TELEGRAM_CHAT_ID) {
            throw new \InvalidArgumentException("Chat ID looks suspicious: $value");
        }
    }
}
