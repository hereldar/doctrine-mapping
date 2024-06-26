<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Interfaces;

interface FieldLike
{
    /**
     * @return non-empty-string
     */
    public function property(): string;
}
