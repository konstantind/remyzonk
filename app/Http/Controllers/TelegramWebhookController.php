<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Infrastructure\Telegram\Commands\CompleteReminderCallbackCommand;
use App\Infrastructure\Telegram\Commands\DeleteReminderCallbackCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\Api;

class TelegramWebhookController extends Controller
{
    public function __construct(private Api $telegram)
    {
    }

    public function handle(Request $request): Response
    {
        $update = $this->telegram->getWebhookUpdate();

        if ($update->isType('callback_query')) {
            $data = $update->getCallbackQuery()->getData();

            if (str_starts_with($data, 'delete_reminder:')) {
                app(DeleteReminderCallbackCommand::class)->make($this->telegram, $update);
                return response('OK');
            }

            if (str_starts_with($data, 'complete_reminder:')) {
                app(CompleteReminderCallbackCommand::class)->make($this->telegram, $update);
                return response('OK');
            }
        }

        $this->telegram->commandsHandler(true);
        return response('OK');
    }
}
