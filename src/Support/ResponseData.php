<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Support;

use GPIbarra\ChatwootSdk\Exceptions\UnexpectedResponseException;
use Saloon\Http\Response;

/**
 * Extracts (possibly enveloped) JSON structures from a response.
 *
 * Chatwoot wraps many payloads in envelopes such as `{payload: ...}`, `{payload: {webhook: ...}}` or
 * `{data: {payload: [...]}}`; the path segments given here walk into those envelopes.
 *
 * @internal
 */
final class ResponseData
{
    /**
     * Decodes the whole response body into an array (an empty body yields an empty array).
     *
     * @return array<array-key, mixed>
     */
    public static function decode(Response $response): array
    {
        $body = trim($response->body());

        if ($body === '' || $body === 'null') {
            return [];
        }

        $decoded = json_decode($body, true);

        if (! is_array($decoded)) {
            throw new UnexpectedResponseException('Expected a JSON object or array, got: '.mb_substr($body, 0, 200));
        }

        return $decoded;
    }

    /**
     * @return array<string, mixed>
     */
    public static function object(Response $response, string ...$path): array
    {
        $value = self::dig(self::decode($response), $path);

        if (! is_array($value)) {
            throw new UnexpectedResponseException(sprintf('Expected a JSON object at "%s".', self::describe($path)));
        }

        /** @var array<string, mixed> $value */
        return $value;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function list(Response $response, string ...$path): array
    {
        $value = self::dig(self::decode($response), $path);

        if (! is_array($value) || ! array_is_list($value)) {
            throw new UnexpectedResponseException(sprintf('Expected a JSON array at "%s".', self::describe($path)));
        }

        $items = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                /** @var array<string, mixed> $item */
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Returns the value at the path, or null when any segment is missing.
     *
     * @param  array<array-key, mixed>  $data
     * @param  array<array-key, string>  $path
     */
    public static function dig(array $data, array $path): mixed
    {
        $value = $data;

        foreach ($path as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * @param  array<array-key, string>  $path
     */
    private static function describe(array $path): string
    {
        return $path === [] ? '(root)' : implode('.', $path);
    }
}
