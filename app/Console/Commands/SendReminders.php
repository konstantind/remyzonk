<?php declare(strict_types=1);

namespace App\Console\Commands;

use app\Application\UseCase\SendRemindersUseCase\SendRemindersUseCase;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due reminders via Telegram';

    public function __construct(
        private SendRemindersUseCase $sendRemindersUseCase
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = new \DateTimeImmutable();
        $this->sendRemindersUseCase->execute($now);
        $this->info("Reminders processed.");
    }
}
