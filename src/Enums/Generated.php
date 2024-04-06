<?php

declare(strict_types=1);

namespace Hereldar\DoctrineMapping\Enums;

/**
 * The mode of automatically generate IDs.
 *
 * TODO: convert to a backed enum when PHP 8.1 is the minimum version
 */
final class Generated
{
    /**
     * The column value is never generated by the database.
     */
    public const Never = 0;

    /**
     * The column value is generated by the database on INSERT, but
     * not on UPDATE.
     */
    public const Insert = 1;

    /**
     * The column value is generated by the database on both INSERT
     * and UDPATE statements.
     */
    public const Always = 2;

    private function __construct(
        private int $value,
    ) {}

    /**
     * @throws \Error
     */
    public static function from(int|string $value): self
    {
        return match ($value) {
            0, 'NEVER' => new self(self::Never),
            1, 'INSERT' => new self(self::Insert),
            2, 'ALWAYS' => new self(self::Always),
        };
    }

    /**
     * @return int<0, 2>
     */
    public function value(): int
    {
        return $this->value;
    }
}
