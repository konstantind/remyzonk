<?php declare(strict_types=1);

namespace App\Application\Service;

interface ReminderDateTimeParserInterface
{
    /**
     * Парсит входную строку и возвращает \DateTimeImmutable
     *
     * @throws \InvalidArgumentException если не удалось распарсить
     */
    public function parse(string $input, \DateTimeImmutable $now): \DateTimeImmutable;
}
