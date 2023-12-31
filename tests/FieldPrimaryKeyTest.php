<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests;

use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Field;
use Hereldar\DoctrineMapping\Internals\Resolvers\EntityResolver;
use Hereldar\DoctrineMapping\Tests\Entities\Product;

final class FieldPrimaryKeyTest extends TestCase
{
    public function testDefinedPrimaryKey(): void
    {
        $entity = Entity::of(
            class: Product::class,
        )->withFields(
            Field::of(property: 'id', primaryKey: true),
            Field::of(property: 'categoryId', primaryKey: false),
        );

        [$resolvedEntity] = EntityResolver::resolve($entity);

        self::assertTrue($resolvedEntity->fields[0]->primaryKey);
        self::assertFalse($resolvedEntity->fields[1]->primaryKey);
    }

    public function testUndefinedPrimaryKey(): void
    {
        $entity = Entity::of(
            class: Product::class,
        )->withFields(
            Field::of(property: 'id'),
            Field::of(property: 'categoryId'),
        );

        [$resolvedEntity] = EntityResolver::resolve($entity);

        self::assertFalse($resolvedEntity->fields[0]->primaryKey);
        self::assertFalse($resolvedEntity->fields[1]->primaryKey);
    }
}
