<?php declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Api;

class TelegramSetWebhookCommand extends Command
{
    protected $signature = 'telegram:setWebhook';
    protected $description = 'Устанавливает Telegram webhook';

    /**
     * Execute the console command.
     */
    public function handle(Api $telegram): int
    {
        $webhookUrl = config('app.url') . '/api/telegram/webhook';

        try {
            $response = $telegram->setWebhook([
                'url' => $webhookUrl,
            ]);

            $this->info('Webhook установлен: ' . $webhookUrl);
            $this->line('Ответ Telegram: ' . json_encode($response));

            return 0;
        } catch (\Exception $e) {
            $this->error('Ошибка установки webhook: ' . $e->getMessage());
            return 1;
        }
    }
}
