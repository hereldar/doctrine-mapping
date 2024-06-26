<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests\Field;

use Hereldar\DoctrineMapping\Tests\Field\Updatable\DefinedUpdatable;
use Hereldar\DoctrineMapping\Tests\Field\Updatable\UndefinedUpdatable;
use Hereldar\DoctrineMapping\Tests\TestCase;

final class FieldUpdatableTest extends TestCase
{
    public function testDefinedUpdatable(): void
    {
        $metadata = $this->loadClassMetadata(DefinedUpdatable::class);

        self::assertFieldNotUpdatable($metadata, 'id', false);
        self::assertFieldNotUpdatable($metadata, 'field', true);
    }

    public function testUndefinedUpdatable(): void
    {
        $metadata = $this->loadClassMetadata(UndefinedUpdatable::class);

        self::assertFieldNotUpdatable($metadata, 'field', false);
    }
}
