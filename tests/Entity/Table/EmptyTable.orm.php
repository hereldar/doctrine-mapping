<?php

use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Tests\Entity\Table\EmptyTable;

return Entity::of(
    class: EmptyTable::class,
    table: '',
);
