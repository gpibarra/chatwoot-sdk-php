<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Users;

use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get a user.
 *
 * `GET /platform/api/v1/users/{userId}`
 */
final class GetUserRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $userId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/users/{$this->userId}";
    }

    public function createDtoFromResponse(Response $response): User
    {
        return User::fromArray(ResponseData::object($response));
    }
}
