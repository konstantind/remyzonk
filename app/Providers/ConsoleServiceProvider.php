<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SendReminders;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            SendReminders::class,
        ]);
    }

    public function boot()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('reminders:send')->everyMinute();
        });
    }
}
