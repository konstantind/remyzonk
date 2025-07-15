<?php declare(strict_types = 1);

namespace app\Infrastructure\Service;

use App\Application\Service\ReminderDateTimeParserInterface;
use Carbon\CarbonImmutable;

class CarbonReminderDateTimeParser implements ReminderDateTimeParserInterface
{

    public function parse(string $input, \DateTimeImmutable $now): \DateTimeImmutable
    {
        if (preg_match('/завтра/i', $input)) {
            $input = preg_replace('/завтра/i', '', $input);
            $date = CarbonImmutable::instance($now)->addDay();
            $time = trim($input);
            if ($time) {
                $dateTime = CarbonImmutable::parse($date->format('Y-m-d') . ' ' . $time);
            } else {
                $dateTime = $date->startOfDay();
            }
        } else {
            $dateTime = CarbonImmutable::parse($input);
        }

        if (!$dateTime) {
            throw new \InvalidArgumentException("Не удалось распарсить дату");
        }

        return \DateTimeImmutable::createFromMutable($dateTime);
    }
}
