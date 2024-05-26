<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping;

use Hereldar\DoctrineMapping\Enums\Cascade;
use Hereldar\DoctrineMapping\Enums\Fetch;

/**
 * @psalm-immutable
 */
abstract class AbstractIncompleteAssociation implements IncompleteAssociation
{
    /** @param non-empty-string $property */
    protected string $property;

    /** @param list<Cascade> $cascade */
    protected array $cascade;
    protected Fetch $fetch;

    /**
     * @return non-empty-string
     */
    public function property(): string
    {
        return $this->property;
    }

    /**
     * @return list<Cascade>
     */
    public function cascade(): array
    {
        return $this->cascade;
    }

    public function fetch(): Fetch
    {
        return $this->fetch;
    }
}