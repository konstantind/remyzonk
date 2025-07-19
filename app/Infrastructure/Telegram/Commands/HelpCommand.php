<?php declare(strict_types=1);

namespace App\Infrastructure\Telegram\Commands;

use App\Application\Service\ReminderDateTimeParserInterface;
use App\Application\UseCase\CreateReminderUseCase\CreateReminderUseCase;
use App\Application\UseCase\SaveUserUseCase\SaveUserUseCase;
use App\Application\UseCase\SaveUserUseCase\SaveUserUseCaseRequestDTO;
use App\Domain\Repository\UserRepositoryInterface;
use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'Регистрация пользователя в системе';

    public function __construct(
        protected ReminderDateTimeParserInterface $parser,
        protected UserRepositoryInterface $userRepository,
        protected CreateReminderUseCase $createReminderUseCase,
        protected SaveUserUseCase $saveUserUseCase,
    ) {
    }

    public function handle()
    {
        $message = $this->getUpdate()->getMessage();
        $fromUser = $message->getFrom();

        $this->saveUserUseCase->execute(
            new SaveUserUseCaseRequestDTO(
                $fromUser->getId(),
                $fromUser->getUsername(),
            )
        );

        $this->replyWithMessage([
            'text' => "Привет! Я RemyZonk – бот который помогает ничего не забыть!",
        ]);
    }
}
