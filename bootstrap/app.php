<?php declare(strict_types=1);

use App\Console\Commands\SendReminders;
use App\Providers\ConsoleServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        ConsoleServiceProvider::class,
    ])
    ->withCommands([
        SendReminders::class,
    ])
    ->create();
