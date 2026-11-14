<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Support;

/**
 * Tolerant readers for decoded JSON, used by the DTO mappers.
 *
 * Chatwoot omits keys depending on channel type, permissions and edition, so every reader accepts a
 * missing key; the non-nullable ones fall back to a neutral value instead of failing.
 *
 * @internal
 */
final class Cast
{
    /**
     * @param  array<array-key, mixed>  $data
     */
    public static function int(array $data, string $key, int $default = 0): int
    {
        return self::nullableInt($data, $key) ?? $default;
    }

    /**
     * @param  array<array-key, mixed>  $data
     */
    public static function nullableInt(array $data, string $key): ?int
    {
        $value = $data[$key] ?? null;

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value) || (is_string($value) && is_numeric($value))) {
            return (int) $value;
        }

        return null;
    }

    /**
     * @param  array<array-key, mixed>  $data
     */
    public static function string(array $data, string $key, string $default = ''): string
    {
        return self::nullableString($data, $key) ?? $default;
    }

    /**
     * @param  array<array-key, mixed>  $data
     */
    public static function nullableString(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param  array<array-key, mixed>  $data
     */
    public static function bool(array $data, string $key, bool $default = false): bool
    {
        return self::nullableBool($data, $key) ?? $default;
    }

    /**
     * @param  array<array-key, mixed>  $data
     */
    public static function nullableBool(array $data, string $key): ?bool
    {
        $value = $data[$key] ?? null;

        return is_bool($value) ? $value : null;
    }

    /**
     * Returns the value as an array, or an empty array when absent or not an array.
     *
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    public static function array(array $data, string $key): array
    {
        $value = $data[$key] ?? null;

        return is_array($value) ? $value : [];
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @return array<string, mixed>|null
     */
    public static function nullableObject(array $data, string $key): ?array
    {
        $value = $data[$key] ?? null;

        if (! is_array($value) || ($value !== [] && array_is_list($value))) {
            return null;
        }

        /** @var array<string, mixed> $value */
        return $value;
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @return list<array<string, mixed>>
     */
    public static function listOfObjects(array $data, string $key): array
    {
        $items = [];

        foreach (self::array($data, $key) as $item) {
            if (is_array($item)) {
                /** @var array<string, mixed> $item */
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @return list<string>
     */
    public static function listOfStrings(array $data, string $key): array
    {
        $items = [];

        foreach (self::array($data, $key) as $item) {
            if (is_string($item)) {
                $items[] = $item;
            }
        }

        return $items;
    }
}
