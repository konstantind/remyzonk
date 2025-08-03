<?php declare(strict_types=1);

namespace App\Infrastructure\Helper;

class EntityReflectionHelper
{
    public static function setPrivateProperty(object $object, string $property, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }

    public static function setId(object $entity, int $id): void
    {
        self::setPrivateProperty($entity, 'id', $id);
    }

    public static function setMessageId(object $entity, ?int $id): void
    {
        self::setPrivateProperty($entity, 'messageId', $id);
    }
}
