<?php declare(strict_types = 1);

namespace App\Infrastructure\Service;

use App\Application\Service\ReminderDateTimeParserInterface;
use Carbon\CarbonImmutable;

class CarbonReminderDateTimeParser implements ReminderDateTimeParserInterface
{

    public function parse(string $input, \DateTimeImmutable $now): \DateTimeImmutable
    {
        $input = mb_strtolower(trim($input));
        $nowCarbon = CarbonImmutable::instance($now);

        if (str_starts_with($input, '/remind')) {
            $input = trim(substr($input, strlen('/remind')));
        }

        if (preg_match('/^сегодня\s*/iu', $input)) {
            $input = trim(preg_replace('/^сегодня\s*/iu', '', $input));
            return $this->combineDateAndTime($nowCarbon, $input);
        }

        if (preg_match('/^завтра\s*/iu', $input)) {
            $input = trim(preg_replace('/^завтра\s*/iu', '', $input));
            return $this->combineDateAndTime($nowCarbon->addDay(), $input);
        }

        $datePatterns = [
            '/(\d{2})[.\-](\d{2})[.\-](\d{4})/',    // 25.07.2025 или 25-07-2025
            '/(\d{2})[.\-](\d{2})[.\-](\d{2})/',    // 25.07.25 или 25-07-25
            '/(\d{2})[.\-](\d{2})/',                // 25.07 или 25-07
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $input, $matches)) {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3] ?? (string) $now->format('Y');

                // Обработка короткого года
                if (strlen($year) === 2) {
                    $year = '20' . $year;
                }

                $date = CarbonImmutable::createFromFormat('Y-m-d', "$year-$month-$day");
                if (!$date) {
                    break;
                }

                // Удалим дату из строки, остальное — возможно, время
                $input = trim(str_replace($matches[0], '', $input));
                return $this->combineDateAndTime($date, $input);
            }
        }

        return $this->combineDateAndTime($nowCarbon, $input);
    }

    private function combineDateAndTime(CarbonImmutable $date, string $input): \DateTimeImmutable
    {
        if (preg_match('/(?:в\s*)?(\d{1,2}:\d{2})/iu', $input, $matches)) {
            $time = $matches[1];
            $dt = CarbonImmutable::parse($date->format('Y-m-d') . ' ' . $time);
        } else {
            throw new \InvalidArgumentException("Некорректный формат времени");
        }

        return new \DateTimeImmutable($dt->format('Y-m-d H:i:s'));
    }
}
