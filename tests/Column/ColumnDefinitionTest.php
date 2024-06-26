<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests\Column;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Tests\Column\Definition\DefinedDefinition;
use Hereldar\DoctrineMapping\Tests\Column\Definition\EmptyDefinition;
use Hereldar\DoctrineMapping\Tests\Column\Definition\UndefinedDefinition;
use Hereldar\DoctrineMapping\Tests\TestCase;

final class ColumnDefinitionTest extends TestCase
{
    public function testDefinedColumnDefinition(): void
    {
        $metadata = $this->loadClassMetadata(DefinedDefinition::class);

        self::assertFieldColumnDefinition($metadata, 'field', 'CHAR(32) NOT NULL');
    }

    public function testUndefinedColumnDefinition(): void
    {
        $metadata = $this->loadClassMetadata(UndefinedDefinition::class);

        self::assertFieldColumnDefinition($metadata, 'field', null);
    }

    public function testEmptyColumnDefinition(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessage("Invalid file 'EmptyDefinition.orm.php': Empty column definition for field 'field'");

        $this->loadClassMetadata(EmptyDefinition::class);
    }
}
