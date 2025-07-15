<?php declare(strict_types=1);

namespace App\Http\Controllers;

use app\Application\UseCase\CreateReminderUseCase\CreateReminderRequestDTO;
use App\Application\UseCase\CreateReminderUseCase\CreateReminderUseCase;
use app\Domain\ValueObject\ChatId;
use app\Domain\ValueObject\UserId;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

class TelegramWebhookController extends Controller
{
    public function __construct(
        private Api $telegram,
        private CreateReminderUseCase $createReminderUseCase,
    ) {}

    public function handle(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate();
        $message = $update->getMessage();

        if (!$message || !$message->has('text')) {
            return response('OK');
        }

        $text = $message['text'];
        $chatId = $message['chat']['id'];
        $fromId = $message['from']['id'];

        // Простейший парсер команды
        if (str_starts_with($text, '/remind')) {
            $parts = explode(' ', $text, 3);

            if (count($parts) < 3) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Неверный формат. Пример: /remind 15:30 Позвонить маме",
                ]);
                return response('OK');
            }

            [$cmd, $time, $remindText] = $parts;

            $today = new \DateTimeImmutable();

            try {
                $remindAt = new \DateTimeImmutable($today->format('Y-m-d') . ' ' . $time);
            } catch (\Throwable) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => "Некорректное время. Пример: /remind 15:30 Позвонить маме",
                ]);
                return response('OK');
            }

            $dto = new CreateReminderRequestDTO(
                chatId: new ChatId($chatId),
                creatorId: new UserId($fromId),
                targetUserId: null,
                text: $remindText,
                remindAt: $remindAt,
            );

            $this->createReminderUseCase->execute($dto);

            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "✅ Напоминание сохранено на $time: $remindText",
            ]);
        }

        return response('OK');
    }
}
