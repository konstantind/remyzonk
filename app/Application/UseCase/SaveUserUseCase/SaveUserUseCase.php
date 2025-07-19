<?php declare(strict_types = 1);

namespace App\Application\UseCase\SaveUserUseCase;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserId;

class SaveUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function execute(SaveUserUseCaseRequestDTO $dto): void
    {
        $existing = $this->userRepository->findById($dto->id);

        if ($existing === null) {
            $user = new User(
                new UserId($dto->id),
                $dto->username
            );
            $this->userRepository->save($user);
        }
    }
}
