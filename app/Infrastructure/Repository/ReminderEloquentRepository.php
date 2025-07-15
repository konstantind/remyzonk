<?php declare(strict_types=1);

namespace app\Infrastructure\Repository;

use app\Domain\Entity\Reminder;
use app\Domain\Repository\ReminderRepositoryInterface;
use app\Domain\ValueObject\ChatId;
use app\Domain\ValueObject\Status;
use app\Domain\ValueObject\UserId;
use App\Models\Reminder as ReminderModel;

class ReminderEloquentRepository implements ReminderRepositoryInterface
{

    public function save(Reminder $reminder): void
    {
        $model = null;

        if ($reminder->getId() !== null) {
            $model = ReminderModel::find($reminder->getId());
        }

        if (!$model) {
            $model = new ReminderModel();
        }

        $model->chat_id = $reminder->getChatId()->value();
        $model->creator_id = $reminder->getCreatorId()->value();
        $model->target_user_id = $reminder->getTargetUserId()->value();
        $model->text = $reminder->getText();
        $model->remind_at = $reminder->getRemindAt()->format('Y-m-d H:i:s');
        $model->status = $reminder->getStatus()->value();

        $model->save();

        if ($reminder->getId() === null) {
            $reflection = new \ReflectionClass($reminder);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($reminder, $model->id);
        }
    }

    /**
     * @return Reminder[]
     */
    public function findPending(\DateTimeImmutable $now): iterable
    {
        $models = ReminderModel::query()
            ->where('status', Status::pending()->value())
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

        $reflection = new \ReflectionClass($reminder);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($reminder, $model->id);

        return $reminder;
    }
}
