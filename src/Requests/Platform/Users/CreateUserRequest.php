<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Users;

use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create a user (or attach an existing user with the same email to the platform app).
 *
 * `POST /platform/api/v1/users`
 */
final class CreateUserRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return '/platform/api/v1/users';
    }

    public function createDtoFromResponse(Response $response): User
    {
        return User::fromArray(ResponseData::object($response));
    }
}
