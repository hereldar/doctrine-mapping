<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests;

use Hereldar\DoctrineMapping\Embedded;
use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Exceptions\MappingException;
use Hereldar\DoctrineMapping\Internals\Resolvers\EntityResolver;
use Hereldar\DoctrineMapping\Tests\Entities\User;

final class EmbeddedPropertyTest extends TestCase
{
    public function testExistingProperty(): void
    {
        $entity = Entity::of(
            class: User::class,
        )->withFields(
            Embedded::of('id'),
        );

        [$resolvedEntity] = EntityResolver::resolve($entity);

        self::assertSame('id', $resolvedEntity->fields[0]->property);
    }

    public function testNonExistingProperty(): void
    {
        $entity = Entity::of(
            class: User::class,
        )->withFields(
            Embedded::of('nonExistingProperty'),
        );

        self::assertException(
            MappingException::propertyNotFound(User::class, 'nonExistingProperty'),
            fn () => EntityResolver::resolve($entity),
        );
    }

    public function testEmptyProperty(): void
    {
        $entity = Entity::of(
            class: User::class,
        )->withFields(
            Embedded::of(''),
        );

        self::assertException(
            MappingException::emptyPropertyName(User::class),
            fn () => EntityResolver::resolve($entity),
        );
    }
}
