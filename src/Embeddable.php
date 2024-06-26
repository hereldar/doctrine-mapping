<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Interfaces\EntityLike;
use Hereldar\DoctrineMapping\Interfaces\FieldLike;
use Hereldar\DoctrineMapping\Internals\Resolvers\ClassResolver;
use ReflectionClass;

/**
 * @psalm-immutable
 */
final class Embeddable implements EntityLike
{
    private function __construct(
        private ReflectionClass $class,
        private Fields $fields,
        private Embeddables $embeddedEmbeddables,
    ) {}

    /**
     * @param class-string $class
     *
     * @throws DoctrineMappingException
     */
    public static function of(
        string|ReflectionClass $class,
    ): self {
        if (\is_string($class)) {
            $class = ClassResolver::resolve($class);
        }

        return new self(
            $class,
            Fields::empty(),
            Embeddables::empty(),
        );
    }

    /**
     * @param non-empty-list<FieldLike> $fields
     *
     * @throws DoctrineMappingException
     */
    public function withFields(
        FieldLike ...$fields,
    ): self {
        $fieldCollection = Fields::of($this, ...$fields);

        return new self(
            $this->class,
            $fieldCollection,
            Embeddables::fromFields($fieldCollection),
        );
    }

    public function class(): ReflectionClass
    {
        return $this->class;
    }

    public function className(): string
    {
        return $this->class->name;
    }

    public function classSortName(): string
    {
        return $this->class->getShortName();
    }

    public function fields(): Fields
    {
        return $this->fields;
    }

    public function embeddedEmbeddables(): Embeddables
    {
        return $this->embeddedEmbeddables;
    }
}
