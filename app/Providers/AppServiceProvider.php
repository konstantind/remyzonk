<?php declare(strict_types=1);

namespace App\Providers;

use App\Application\Service\ReminderDateTimeParserInterface;
use App\Application\Service\ReminderNotifierInterface;
use App\Domain\Repository\ReminderRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Infrastructure\Repository\ReminderEloquentRepository;
use App\Infrastructure\Repository\UserEloquentRepository;
use App\Infrastructure\Service\CarbonReminderDateTimeParser;
use App\Infrastructure\Service\TelegramReminderNotifier;
use App\Infrastructure\Telegram\Commands\HelpCommand;
use App\Infrastructure\Telegram\Commands\RemindCommand;
use Illuminate\Support\ServiceProvider;
use Telegram\Bot\Laravel\Facades\Telegram;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserEloquentRepository::class
        );

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
        Telegram::addCommand(RemindCommand::class);
        Telegram::addCommand(HelpCommand::class);
    }
}
