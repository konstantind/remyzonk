<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramWebhookController extends Controller
{
    public function __construct(private Api $telegram)
    {
    }

    public function handle(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate();
        $this->telegram->commandsHandler(true);
        return response('OK');
    }
}
