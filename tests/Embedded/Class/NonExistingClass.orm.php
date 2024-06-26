<?php

declare(strict_types=1);

use Hereldar\DoctrineMapping\Embedded;
use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\NonExistingClass;

return Entity::of(
    class: NonExistingClass::class,
)->withFields(
    Embedded::of(property: 'id', class: 'NonExisting'),
);
