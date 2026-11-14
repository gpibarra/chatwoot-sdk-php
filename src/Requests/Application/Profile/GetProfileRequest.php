<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Profile;

use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get the profile of the token's user.
 *
 * `GET /api/v1/profile`
 */
final class GetProfileRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/api/v1/profile';
    }

    public function createDtoFromResponse(Response $response): User
    {
        return User::fromArray(ResponseData::object($response));
    }
}
