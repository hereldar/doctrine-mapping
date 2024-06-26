<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Tests\Embedded;

use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\AnonymousClass;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\EmptyClass;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\ExistingClass;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\ExistingField;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\ExistingId;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\MissingClass;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\NonExistingClass;
use Hereldar\DoctrineMapping\Tests\Embedded\Class\UndefinedClass;
use Hereldar\DoctrineMapping\Tests\TestCase;

final class EmbeddedClassTest extends TestCase
{
    public function testExistingClass(): void
    {
        $metadata = $this->loadClassMetadata(ExistingClass::class);

        self::assertEmbeddedClass($metadata, 'id', ExistingId::class);
        self::assertEmbeddedClass($metadata, 'field', ExistingField::class);
    }

    public function testNonExistingClass(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessage("Invalid file 'NonExistingClass.orm.php': Class 'NonExisting' does not exist");

        $this->loadClassMetadata(NonExistingClass::class);
    }

    public function testEmptyClass(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessage("Invalid file 'EmptyClass.orm.php': Class name cannot be empty");

        $this->loadClassMetadata(EmptyClass::class);
    }

    public function testAnonymousClass(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessageMatches("/Invalid file 'AnonymousClass.orm.php': Class 'class@anonymous[^']*' is anonymous/");

        $this->loadClassMetadata(AnonymousClass::class);
    }

    public function testUndefinedClass(): void
    {
        $metadata = $this->loadClassMetadata(UndefinedClass::class);

        self::assertEmbeddedClass($metadata, 'id', ExistingId::class);
        self::assertEmbeddedClass($metadata, 'field', ExistingField::class);
    }

    public function testMissingClass(): void
    {
        $this->expectException(DoctrineMappingException::class);
        $this->expectExceptionMessage("Invalid file 'MissingClass.orm.php': Missing class attribute for property 'id' on class 'MissingClass'");

        $this->loadClassMetadata(MissingClass::class);
    }
}
