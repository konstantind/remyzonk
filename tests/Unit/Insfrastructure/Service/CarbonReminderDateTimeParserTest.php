<?php declare(strict_types = 1);

namespace Tests\Unit\Insfrastructure\Service;

use App\Infrastructure\Service\CarbonReminderDateTimeParser;
use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class CarbonReminderDateTimeParserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::create(2025, 7, 19, 12, 0, 0));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('reminderProvider')]
    public function testParsingReminderStrings(string $input, string $expectedDateTime): void
    {
        $parser = new CarbonReminderDateTimeParser();
        $now = new \DateTimeImmutable('2025-07-19 12:00:00');

        $result = $parser->parse($input, $now);

        $this->assertInstanceOf(\DateTimeImmutable::class, $result);
        $this->assertEquals($expectedDateTime, $result->format('Y-m-d H:i:s'));
    }

    public static function reminderProvider(): array
    {
        return [
            ['/remind 15:30 позвонить родителям',           '2025-07-19 15:30:00'],
            ['/remind сегодня в 15:30 позвонить родителям', '2025-07-19 15:30:00'],
            ['/remind завтра в 15:30 позвонить родителям',  '2025-07-20 15:30:00'],
            ['/remind 25-07 15:30 позвонить родителям',     '2025-07-25 15:30:00'],
            ['/remind 25-07-2025 15:30 позвонить родителям','2025-07-25 15:30:00'],
            ['/remind 25-07-2026 15:30 позвонить родителям','2026-07-25 15:30:00'],
            ['/remind 25.07 15:30 позвонить родителям',     '2025-07-25 15:30:00'],
            ['/remind 25.07.25 15:30 позвонить родителям',  '2025-07-25 15:30:00'],
            ['/remind 25.07.2025 15:30 позвонить родителям','2025-07-25 15:30:00'],
            ['/remind 25.07.2026 15:30 позвонить родителям','2026-07-25 15:30:00'],
        ];
    }

    public function testExceptionThrownWhenTimeIsMissing(): void
    {
        $parser = new CarbonReminderDateTimeParser();
        $now = new \DateTimeImmutable('2025-07-19 12:00:00');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Некорректный формат времени');

        $parser->parse('/remind 25.07', $now);
    }
}
