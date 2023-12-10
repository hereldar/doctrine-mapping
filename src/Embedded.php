<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping;

use Hereldar\DoctrineMapping\Internals\Exceptions\FalseTypeError;

/**
 * @psalm-immutable
 */
final class Embedded
{
    /**
     * @param non-empty-string $property
     * @param ?class-string $class
     * @param non-empty-string|false|null $columnPrefix
     * @param ?non-empty-list<Field|Embedded> $properties
     */
    private function __construct(
        private string $property,
        private ?string $class = null,
        private string|bool|null $columnPrefix = null,
        private ?array $properties = null,
    ) {}

    /**
     * @param ?class-string $class
     * @param non-empty-string|false|null $columnPrefix
     * @param ?non-empty-list<Field|Embedded> $properties
     */
    public static function of(
        string $property,
        ?string $class = null,
        string|bool|null $columnPrefix = null,
        ?array $properties = null,
    ): self {
        if ($columnPrefix === true) {
            throw new FalseTypeError('Embedded::of()', 3, '$columnPrefix');
        }

        return new self(...func_get_args());
    }

    /**
     * @return non-empty-string
     */
    public function property(): string
    {
        return $this->property;
    }

    /**
     * @return ?class-string
     */
    public function class(): ?string
    {
        return $this->class;
    }

    /**
     * @return non-empty-string|false|null
     */
    public function columnPrefix(): string|bool|null
    {
        return $this->columnPrefix;
    }

    /**
     * @return ?non-empty-list<Field|Embedded>
     */
    public function properties(): ?array
    {
        return $this->properties;
    }
}