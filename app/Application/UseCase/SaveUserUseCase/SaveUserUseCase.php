<?php declare(strict_types = 1);

namespace App\Application\UseCase\SaveUserUseCase;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\UserId;

class SaveUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(SaveUserUseCaseRequestDTO $dto): void
    {
        $existingUser = $this->userRepository->findById($dto->id);

        $privateChatId = null;

        if ($dto->privateChatId !== null) {
            $privateChatId = new ChatId($dto->privateChatId);
        } elseif ($existingUser !== null) {
            $privateChatId = $existingUser->getPrivateChatId();
        }

        $user = new User(
            new UserId($dto->id),
            $dto->username,
            $privateChatId,
        );

        $this->userRepository->save($user);
    }
}
