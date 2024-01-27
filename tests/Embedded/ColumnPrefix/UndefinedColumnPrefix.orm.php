<?php

use Hereldar\DoctrineMapping\Embedded;
use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Tests\Embedded\ColumnPrefix\UndefinedColumnPrefix;

return Entity::of(
    class: UndefinedColumnPrefix::class,
)->withFields(
    Embedded::of(property: 'field'),
);
