<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Profile;

use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update the profile of the token's user.
 *
 * `PUT /api/v1/profile`
 */
final class UpdateProfileRequest extends JsonBodyRequest
{
    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/profile';
    }

    public function createDtoFromResponse(Response $response): User
    {
        return User::fromArray(ResponseData::object($response));
    }
}
