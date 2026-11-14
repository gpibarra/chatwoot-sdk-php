<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Exceptions;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use Throwable;

/**
 * Thrown for every non-2xx Chatwoot response.
 *
 * Chatwoot error bodies are not uniform. Depending on the code path they look like:
 *  - `{"error": "Resource could not be found"}` (not found, unauthorized, could-not-create),
 *  - `{"message": "Name can't be blank", "attributes": ["name"]}` (model validation failures),
 *  - `{"errors": ["You need to sign in or sign up before continuing."]}` (authentication),
 *  - `{"error": {"name": ["can't be blank"]}}` (some controllers render the errors hash).
 * All of them are normalised into {@see self::getErrors()} and a readable message.
 */
class ChatwootException extends RequestException
{
    /**
     * @param  list<string>  $errors
     * @param  list<string>  $attributes
     */
    public function __construct(
        Response $response,
        string $message,
        private readonly array $errors = [],
        private readonly array $attributes = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($response, $message, $response->status(), $previous);
    }

    public static function fromResponse(Response $response, ?Throwable $previous = null): self
    {
        $body = self::decode($response);
        $errors = self::extractErrors($body);
        $attributes = [];

        if (isset($body['attributes']) && is_array($body['attributes'])) {
            foreach ($body['attributes'] as $attribute) {
                if (is_string($attribute)) {
                    $attributes[] = $attribute;
                }
            }
        }

        $summary = $errors !== [] ? implode('; ', $errors) : ($response->getPsrResponse()->getReasonPhrase() ?: 'Request failed');
        $message = sprintf('Chatwoot API error (HTTP %d): %s', $response->status(), $summary);

        $class = match (true) {
            $response->status() === 401 || $response->status() === 403 => AuthenticationException::class,
            $response->status() === 404 => NotFoundException::class,
            $response->status() === 422 => ValidationException::class,
            default => self::class,
        };

        return new $class($response, $message, $errors, $attributes, $previous);
    }

    /**
     * Human-readable error messages extracted from the body.
     *
     * @return list<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Attribute names reported by model validation failures (`attributes` key), if any.
     *
     * @return list<string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * The decoded error body (empty when the body is not JSON).
     *
     * @return array<array-key, mixed>
     */
    public function getBody(): array
    {
        return self::decode($this->getResponse());
    }

    /**
     * @return array<array-key, mixed>
     */
    private static function decode(Response $response): array
    {
        $decoded = json_decode($response->body(), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param  array<array-key, mixed>  $body
     * @return list<string>
     */
    private static function extractErrors(array $body): array
    {
        $errors = [];

        foreach (['error', 'message', 'errors'] as $key) {
            if (array_key_exists($key, $body)) {
                array_push($errors, ...self::flatten($body[$key], null));
            }
        }

        return array_values(array_unique($errors));
    }

    /**
     * @return list<string>
     */
    private static function flatten(mixed $value, ?string $prefix): array
    {
        if (is_string($value) && $value !== '') {
            return [$prefix !== null ? $prefix.' '.$value : $value];
        }

        if (! is_array($value)) {
            return [];
        }

        $messages = [];

        foreach ($value as $key => $item) {
            $nested = is_string($key) && ! array_is_list($value) ? $key : $prefix;
            array_push($messages, ...self::flatten($item, $nested));
        }

        return $messages;
    }
}
