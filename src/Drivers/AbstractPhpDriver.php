<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Drivers;

use Doctrine\ORM\Mapping\ClassMetadata as OrmClassMetadata;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\FileLocator;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\MappingException as PersistenceMappingException;
use Hereldar\DoctrineMapping\Embeddable;
use Hereldar\DoctrineMapping\Entity;
use Hereldar\DoctrineMapping\Internals\Exceptions\MappingException;
use Hereldar\DoctrineMapping\Internals\MetadataFactory;
use Hereldar\DoctrineMapping\MappedSuperclass;
use Throwable;

abstract class AbstractPhpDriver implements MappingDriver
{
    protected FileLocator $locator;

    /**
     * @var array<string, Entity|MappedSuperclass|Embeddable>
     *
     * @psalm-var array<class-string, Entity|MappedSuperclass|Embeddable>
     */
    protected array $classCache = [];

    /**
     * Retrieves the locator used to discover mapping files by className.
     */
    public function getLocator(): FileLocator
    {
        return $this->locator;
    }

    /**
     * Sets the locator used to discover mapping files by className.
     */
    public function setLocator(FileLocator $locator): void
    {
        $this->locator = $locator;
    }

    /**
     * @throws PersistenceMappingException
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata): void
    {
        if (!isset($this->classCache[$className])) {
            try {
                $this->loadMappingFile($className);
            } catch (Throwable $exception) {
                $fileName = $this->locator->findMappingFile($className);
                throw MappingException::invalidFile($fileName, $exception);
            }
        }

        $entity = $this->classCache[$className];
        \assert($metadata instanceof OrmClassMetadata);

        try {
            MetadataFactory::fillMetadataObject($entity, $metadata);
        } catch (Throwable $exception) {
            throw MappingException::invalidMetadata($className, $exception);
        }
    }

    public function getAllClassNames(): ?array
    {
        if ([] === $this->classCache) {
            return $this->locator->getAllClassNames('');
        }

        return \array_values(\array_unique(\array_merge(
            \array_keys($this->classCache),
            $this->locator->getAllClassNames('')
        )));
    }

    public function isTransient($className): bool
    {
        if (isset($this->classCache[$className])) {
            return false;
        }

        return !$this->locator->fileExists($className);
    }

    /**
     * @throws PersistenceMappingException
     */
    protected function loadMappingFile(string $className): void
    {
        $fileName = $this->locator->findMappingFile($className);

        $entity = include $fileName;

        $result = [];
        if ($entity instanceof Entity
            || $entity instanceof MappedSuperclass
            || $entity instanceof Embeddable) {
            $result[$entity->className()] = $entity;
            foreach ($entity->embeddedEmbeddables() as $embeddable) {
                $result[$embeddable->className()] = $embeddable;
            }
        }

        if (!isset($result[$className])) {
            throw MappingException::metadataNotFound($className);
        }

        foreach ($result as $clsName => $cls) {
            $this->classCache[$clsName] = $cls;
        }
    }
}
