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
        FieldLike ...$fields,
    ): self {
        self::ensureFieldsAreNotDuplicated($entity, $fields);
        $fieldProperties = self::ensurePropertiesExist($entity, $fields);
        $fields = self::completeIncompleteFields($entity, $fields, $fieldProperties);

        return new self(...$fields);
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @param list<FieldLike> $fields
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
     * @param list<FieldLike> $fields
     *
     * @return list<ReflectionProperty>
     *
     * @throws DoctrineMappingException
     */
    private static function ensurePropertiesExist(
        EntityLike $entity,
        array $fields,
    ): array {
        $entityClass = $entity->class();
        $fieldProperties = [];

        foreach ($fields as $field) {
            $propertyName = $field->property();
            try {
                $fieldProperties[] = $entityClass->getProperty($propertyName);
            } catch (ReflectionException) {
                throw MappingException::propertyNotFound(
                    $entity->classSortName(),
                    $propertyName
                );
            }
        }

        return $fieldProperties;
    }

    /**
     * @param list<FieldLike> $fields
     * @param list<ReflectionProperty> $fieldProperties
     *
     * @return list<FieldLike>
     *
     * @throws DoctrineMappingException
     */
    private static function completeIncompleteFields(
        EntityLike $entity,
        array $fields,
        array $fieldProperties,
    ): array {
        foreach ($fields as $i => $field) {
            if ($field instanceof IncompleteEmbedded) {
                $property = $fieldProperties[$i];
                $propertyType = $property->getType();

                if (!$propertyType instanceof ReflectionNamedType) {
                    throw MappingException::missingClassAttribute(
                        $entity->classSortName(),
                        $property->name,
                    );
                }

                $fields[$i] = $field->withClass($propertyType->getName());
            }
            if ($field instanceof IncompleteAssociation) {
                $property = $fieldProperties[$i];
                $propertyType = $property->getType();

                if (!$propertyType instanceof ReflectionNamedType) {
                    throw MappingException::missingTargetEntity(
                        $entity->classSortName(),
                        $property->name,
                    );
                }

                $fields[$i] = $field->withTargetEntity($propertyType->getName());
            }
        }

        return $fields;
    }
}
