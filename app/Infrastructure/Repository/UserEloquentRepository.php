<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\UserId;
use App\Infrastructure\Models\User as UserModel;

class UserEloquentRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $model = UserModel::where('telegram_id', $id)->first();

        if (!$model) {
            return null;
        }

        return new User(
            new UserId($model->telegram_id),
            $model->username,
            $model->private_chat_id !== null ? new ChatId($model->private_chat_id) : null,
        );
    }

    public function findByUsername(string $username): ?User
    {
        $model = UserModel::where('username', $username)->first();

        if (!$model) {
            return null;
        }

        return new User(
            new UserId($model->telegram_id),
            $model->username,
            $model->private_chat_id !== null ? new ChatId($model->private_chat_id) : null,
        );
    }

    public function save(User $user): void
    {
        UserModel::updateOrCreate(
            [
                'telegram_id' => $user->getId()->value()
            ],
            [
                'username' => $user->getUsername(),
                'private_chat_id' => $user->getPrivateChatId()?->value()
            ]
        );
    }
}
