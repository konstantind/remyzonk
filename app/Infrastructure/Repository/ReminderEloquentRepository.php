<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Reminder;
use App\Domain\Exception\ReminderNotFoundException;
use App\Domain\Repository\ReminderRepositoryInterface;
use App\Domain\ValueObject\ChatId;
use App\Domain\ValueObject\Status;
use App\Domain\ValueObject\UserId;
use App\Infrastructure\Exception\ReminderUpdateFailedException;
use App\Infrastructure\Helper\EntityReflectionHelper;
use App\Infrastructure\Models\Reminder as ReminderModel;

class ReminderEloquentRepository implements ReminderRepositoryInterface
{

    public function save(Reminder $reminder): void
    {
        $model = new ReminderModel();

        $model->chat_id = $reminder->getChatId()->value();
        $model->creator_id = $reminder->getCreatorId()->value();
        $model->target_user_id = $reminder->getTargetUserId()->value();
        $model->text = $reminder->getText();
        $model->remind_at = $reminder->getRemindAt()->format('Y-m-d H:i:s');
        $model->status = $reminder->getStatus()->value;

        $model->save();

        EntityReflectionHelper::setId($reminder, $model->id);
    }

    public function saveMessageId(Reminder $reminder, int $messageId): void
    {
        $model = ReminderModel::find($reminder->getId());

        if (!$model) {
            throw ReminderNotFoundException::withId($reminder->getId());
        }

        $model->message_id = $messageId;
        $saved = $model->save();

        if (!$saved) {
            throw new ReminderUpdateFailedException("Failed to update message_id field for reminder with ID: " . $reminder->getId());
        }

        EntityReflectionHelper::setMessageId($reminder, $messageId);
    }

    public function complete(int $id): void
    {
        try {
            $model = ReminderModel::find($id);

            if (!$model) {
                throw ReminderNotFoundException::withId($id);
            }

            $model->status = Status::DONE->value;
            $saved = $model->save();

            if (!$saved) {
                throw new \RuntimeException("Failed to save reminder with ID: $id");
            }

        } catch (\Throwable $e) {
            throw new ReminderUpdateFailedException("Failed to update reminder with ID: {$id}");
        }
    }

    public function delete(int $id): void
    {
        $deleted = ReminderModel::destroy($id);

        if ($deleted !== 1) {
            throw ReminderNotFoundException::withId($id);
        }
    }

    public function findById(int $id): ?Reminder
    {
        $model = ReminderModel::find($id);

        if (!$model) {
            throw ReminderNotFoundException::withId($id);
        }

        return $this->mapModelToEntity($model);
    }

    /**
     * @return Reminder[]
     */
    public function findPending(\DateTimeImmutable $now): iterable
    {
        $models = ReminderModel::query()
            ->where('status', Status::PENDING)
            ->where('remind_at', '<=', $now->format('Y-m-d H:i:s'))
            ->get();

        foreach ($models as $model) {
            yield $this->mapModelToEntity($model);
        }
    }

    private function mapModelToEntity(ReminderModel $model): Reminder
    {
        $reminder = new Reminder(
            chatId: new ChatId($model->chat_id),
            creatorId: new UserId($model->creator_id),
            targetUserId: new UserId($model->target_user_id),
            text: $model->text,
            remindAt: \DateTimeImmutable::createFromMutable($model->remind_at),
            status: Status::from($model->status),
        );

        EntityReflectionHelper::setId($reminder, $model->id);
        EntityReflectionHelper::setMessageId($reminder, $model->message_id);

        return $reminder;
    }
}
