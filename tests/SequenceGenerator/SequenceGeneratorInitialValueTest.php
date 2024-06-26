<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests\SequenceGenerator;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Tests\SequenceGenerator\InitialValue\DefinedInitialValue;
use Hereldar\DoctrineMapping\Tests\SequenceGenerator\InitialValue\NegativeInitialValue;
use Hereldar\DoctrineMapping\Tests\SequenceGenerator\InitialValue\UndefinedInitialValue;
use Hereldar\DoctrineMapping\Tests\SequenceGenerator\InitialValue\ZeroInitialValue;
use Hereldar\DoctrineMapping\Tests\TestCase;

final class SequenceGeneratorInitialValueTest extends TestCase
{
    public function testDefinedInitialValue(): void
    {
        $metadata = $this->loadClassMetadata(DefinedInitialValue::class);

        self::assertFieldId($metadata, 'id', true);
        self::assertSame(2, $metadata->generatorType);
        self::assertEquals(5, $metadata->sequenceGeneratorDefinition['initialValue']);
    }

    public function testUndefinedInitialValue(): void
    {
        $metadata = $this->loadClassMetadata(UndefinedInitialValue::class);

        self::assertFieldId($metadata, 'id', true);
        self::assertSame(2, $metadata->generatorType);
        self::assertEquals(1, $metadata->sequenceGeneratorDefinition['initialValue']);
    }

    public function testZeroInitialValue(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessage("Invalid file 'ZeroInitialValue.orm.php': Negative or zero initial value for field 'id'");

        $this->loadClassMetadata(ZeroInitialValue::class);
    }

    public function testNegativeInitialValue(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessage("Invalid file 'NegativeInitialValue.orm.php': Negative or zero initial value for field 'id'");

        $this->loadClassMetadata(NegativeInitialValue::class);
    }
}
