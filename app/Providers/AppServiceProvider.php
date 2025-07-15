<?php declare(strict_types=1);

namespace App\Providers;

use App\Application\Service\ReminderDateTimeParserInterface;
use App\Application\Service\ReminderNotifierInterface;
use app\Domain\Repository\ReminderRepositoryInterface;
use app\Infrastructure\Repository\ReminderEloquentRepository;
use app\Infrastructure\Service\CarbonReminderDateTimeParser;
use app\Infrastructure\Service\TelegramReminderNotifier;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ReminderRepositoryInterface::class,
            ReminderEloquentRepository::class
        );

        $this->app->bind(
            ReminderNotifierInterface::class,
            TelegramReminderNotifier::class
        );

        $this->app->bind(
            ReminderDateTimeParserInterface::class,
            CarbonReminderDateTimeParser::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
