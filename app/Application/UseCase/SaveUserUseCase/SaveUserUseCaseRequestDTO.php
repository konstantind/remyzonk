<?php declare(strict_types=1);

namespace App\Application\UseCase\SaveUserUseCase;

readonly class SaveUserUseCaseRequestDTO
{
    public function __construct(
        public int $id,
        public string $username,
        public ?int $privateChatId = null,
    ) {}
}
