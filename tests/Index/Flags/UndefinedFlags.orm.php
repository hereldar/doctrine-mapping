<?php

use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Index;
use Hereldar\DoctrineMapping\Tests\Index\Flags\UndefinedFlags;

return Entity::of(
    UndefinedFlags::class,
)->withIndexes(
    Index::of(fields: ['field']),
);
