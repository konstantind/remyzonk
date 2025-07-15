<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramWebhookController;

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
