<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests\Fields;

use Doctrine\DBAL\Types\Types;
use Hereldar\DoctrineMapping\AbstractId;
use Hereldar\DoctrineMapping\Fields\UnsignedBigIntegerId;
use Hereldar\DoctrineMapping\Interfaces\FieldLike;
use Hereldar\DoctrineMapping\Tests\TestCase;

final class UnsignedBigIntegerIdTest extends TestCase
{
    public function testParentClass(): void
    {
        $field = UnsignedBigIntegerId::of('id');

        self::assertInstanceOf(FieldLike::class, $field);
        self::assertInstanceOf(AbstractId::class, $field);
    }

    public function testDefaultType(): void
    {
        $field = UnsignedBigIntegerId::of('id');

        self::assertSame(Types::BIGINT, $field->type());
    }

    public function testColumn(): void
    {
        $name = \fake()->word();
        $definition = \fake()->word();
        $comment = \fake()->sentence();

        $field = UnsignedBigIntegerId::of('id')->withColumn(
            name: $name,
            definition: $definition,
            comment: $comment,
        );

        $column = $field->column();

        self::assertSame($name, $column->name());
        self::assertSame($definition, $column->definition());
        self::assertSame($comment, $column->comment());

        self::assertTrue($column->unsigned());

        self::assertFalse($column->unique());
        self::assertFalse($column->nullable());
        self::assertNull($column->length());
        self::assertNull($column->precision());
        self::assertNull($column->scale());
        self::assertNull($column->default());
        self::assertNull($column->fixed());
        self::assertNull($column->charset());
        self::assertNull($column->collation());
    }
}
