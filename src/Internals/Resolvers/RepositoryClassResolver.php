<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Internals\Resolvers;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\Mapping\MappingException as DoctrineMappingException;
use Hereldar\DoctrineMapping\Internals\Exceptions\MappingException;
use ReflectionClass;

/**
 * @internal
 */
final class RepositoryClassResolver
{
    /**
     * @psalm-return ($className is null ? null : ReflectionClass<EntityRepository>)
     * @phpstan-return ($className is null ? null : ReflectionClass<EntityRepository<object>>)
     *
     * @throws DoctrineMappingException
     *
     * @psalm-assert class-string<EntityRepository>|null $className
     */
    public static function resolve(?string $className): ?ReflectionClass
    {
        if (null === $className) {
            return null;
        }

        $class = ClassResolver::resolve($className);

        if (!$class->isSubclassOf(EntityRepository::class)) {
            throw MappingException::invalidRepositoryClass($class->getShortName());
        }

        /** @phpstan-var ReflectionClass<EntityRepository<object>> */
        return $class;
    }
}
