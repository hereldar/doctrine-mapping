<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Enums\Cascade;
use Hereldar\DoctrineMapping\Enums\Fetch;
use Hereldar\DoctrineMapping\Internals\Resolvers\ClassResolver;

final class IncompleteManyToOne extends IncompleteAssociation
{
    /**
     * @param non-empty-string $property
     * @param list<Cascade> $cascade
     * @param non-empty-string|null $inversedBy
     *
     * @internal
     */
    public function __construct(
        protected readonly string $property,
        protected readonly array $cascade,
        protected readonly Fetch $fetch,
        protected readonly ?string $inversedBy,
        protected readonly ?JoinColumns $joinColumns,
    ) {}

    /**
     * @param class-string $class
     *
     * @throws DoctrineMappingException
     */
    public function withTargetEntity(string $class): ManyToOne
    {
        return new ManyToOne(
            $this->property,
            ClassResolver::resolve($class),
            $this->cascade,
            $this->fetch,
            $this->inversedBy,
            $this->joinColumns,
        );
    }

    /**
     * @param non-empty-string|null $name name of the column that holds the foreign key for this relation
     * @param non-empty-string $referencedColumnName name of the primary key that is used for joining of this relation
     * @param non-empty-string|null $columnDefinition SQL fragment that is used when generating the DDL for the column (non-portable)
     * @param array<non-empty-string,mixed> $options platform specific options
     *
     * @throws DoctrineMappingException
     */
    public function withJoinColumn(
        ?string $name = null,
        string $referencedColumnName = 'id',
        bool $unique = false,
        bool $nullable = true,
        mixed $onDelete = null,
        ?string $columnDefinition = null,
        array $options = [],
    ): self {
        return new self(
            $this->property,
            $this->cascade,
            $this->fetch,
            $this->inversedBy,
            JoinColumns::of($this, JoinColumn::of(
                $name,
                $referencedColumnName,
                $unique,
                $nullable,
                $onDelete,
                $columnDefinition,
                $options,
            )),
        );
    }

    /**
     * @throws DoctrineMappingException
     */
    public function withJoinColumns(
        JoinColumn ...$joinColumns,
    ): self {
        return new self(
            $this->property,
            $this->cascade,
            $this->fetch,
            $this->inversedBy,
            JoinColumns::of($this, ...$joinColumns),
        );
    }

    /**
     * @return non-empty-string|null
     */
    public function inversedBy(): ?string
    {
        return $this->inversedBy;
    }

    public function joinColumns(): ?JoinColumns
    {
        return $this->joinColumns;
    }
}
