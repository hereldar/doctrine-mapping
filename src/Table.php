<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Internals\Exceptions\MappingException;
use ReflectionClass;

use function Hereldar\DoctrineMapping\Internals\to_snake_case;

/**
 * @psalm-immutable
 */
final class Table
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $schema
     * @param non-empty-array<non-empty-string,mixed>|null $options
     */
    private function __construct(
        private string $name,
        private ?string $schema,
        private ?array $options,
    ) {}

    /**
     * @param non-empty-string|null $name name of the table
     * @param non-empty-string|null $schema name of the schema that contains the table
     * @param non-empty-array<non-empty-string,mixed>|null $options platform specific options
     *
     * @throws DoctrineMappingException
     */
    public static function of(
        Entity|MappedSuperclass $entity,
        ?string $name = null,
        ?string $schema = null,
        ?array $options = null,
    ): self {
        if (null === $name) {
            $name = to_snake_case($entity->classSortName());
        } elseif ('' === $name) {
            throw MappingException::emptyTableName($entity->className());
        }

        if ('' === $schema) {
            throw MappingException::emptySchemaName($entity->className());
        }

        if (null !== $options) {
            foreach ($options as $key => $value) {
                if (!\is_string($key) || '' === $key) {
                    throw MappingException::invalidTableOption(
                        $entity->className(),
                        $key,
                    );
                }
            }
        }

        return new self(
            $name,
            $schema,
            $options,
        );
    }

    /**
     * @param ReflectionClass<Entity|MappedSuperclass> $entityClass
     */
    public static function empty(
        ReflectionClass $entityClass,
    ): self {
        return new self(
            to_snake_case($entityClass->getShortName()),
            null,
            null,
        );
    }

    /**
     * @return non-empty-string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-string|null
     */
    public function schema(): ?string
    {
        return $this->schema;
    }

    /**
     * @return non-empty-array<non-empty-string,mixed>|null
     */
    public function options(): ?array
    {
        return $this->options;
    }
}
