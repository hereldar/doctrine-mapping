<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Internals\Collection;
use Hereldar\DoctrineMapping\Internals\Exceptions\MappingException;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * @extends Collection<FieldLike>
 */
final class Fields extends Collection
{
    public function __construct(FieldLike ...$fields)
    {
        $this->items = $fields;
    }

    /**
     * @throws DoctrineMappingException
     */
    public static function of(
        EntityLike $entity,
        FieldLike|EmbeddedLike ...$fields,
    ): self {
        self::ensureFieldsAreNotDuplicated($entity, $fields);
        $properties = self::ensurePropertiesExist($entity, $fields);
        $fields = self::completeIncompleteFields($entity, $fields, $properties);

        return new self(...$fields);
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @param list<FieldLike|EmbeddedLike> $fields
     *
     * @throws DoctrineMappingException
     */
    private static function ensureFieldsAreNotDuplicated(
        EntityLike $entity,
        array $fields,
    ): void {
        $properties = [];

        foreach ($fields as $field) {
            $property = $field->property();

            if (isset($properties[$property])) {
                throw MappingException::duplicateProperty(
                    $entity->classSortName(),
                    $property,
                );
            }

            $properties[$property] = true;
        }
    }

    /**
     * @param list<FieldLike|EmbeddedLike> $fields
     *
     * @return list<ReflectionProperty>
     *
     * @throws DoctrineMappingException
     */
    private static function ensurePropertiesExist(
        EntityLike $entity,
        array $fields,
    ): array {
        $class = $entity->class();
        $properties = [];

        foreach ($fields as $field) {
            $propertyName = $field->property();
            try {
                $properties[] = $class->getProperty($propertyName);
            } catch (ReflectionException) {
                throw MappingException::propertyNotFound(
                    $entity->classSortName(),
                    $propertyName,
                );
            }
        }

        return $properties;
    }

    /**
     * @param list<FieldLike|EmbeddedLike> $fields
     * @param list<ReflectionProperty> $properties
     *
     * @return list<FieldLike>
     *
     * @throws DoctrineMappingException
     */
    private static function completeIncompleteFields(
        EntityLike $entity,
        array $fields,
        array $properties,
    ): array {
        $completeFields = [];

        foreach ($fields as $i => $field) {
            if ($field instanceof IncompleteEmbedded) {
                $property = $properties[$i];
                $propertyType = $property->getType();

                if (!$propertyType instanceof ReflectionNamedType) {
                    throw MappingException::missingClassAttribute(
                        $entity->classSortName(),
                        $property->name,
                    );
                }

                $field = $field->withClass($propertyType->getName());
            }
            $completeFields[] = $field;
        }

        return $completeFields;
    }
}
