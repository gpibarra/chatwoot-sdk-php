<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Support;

/**
 * Helpers for building request bodies and queries from optional arguments.
 *
 * @internal
 */
final class Payload
{
    /**
     * Drops null values so optional arguments that were not given are not sent at all.
     *
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    public static function filter(array $values): array
    {
        return array_filter($values, static fn (mixed $value): bool => $value !== null);
    }

    /**
     * Merges explicit arguments (null ones dropped) over the caller's escape-hatch array.
     *
     * @param  array<string, mixed>  $values
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    public static function merge(array $values, array $extra = []): array
    {
        return array_merge($extra, self::filter($values));
    }
}
