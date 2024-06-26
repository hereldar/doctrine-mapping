<?php

declare(strict_types=1);

use Hereldar\DoctrineMapping\MappedSuperclass;
use Hereldar\DoctrineMapping\Tests\MappedSuperclass\RepositoryClass\EmptyRepositoryClass;

return MappedSuperclass::of(
    class: EmptyRepositoryClass::class,
    repositoryClass: '',
);
