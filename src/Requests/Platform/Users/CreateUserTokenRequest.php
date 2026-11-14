<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Users;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get the access token of a user.
 *
 * `POST /platform/api/v1/users/{userId}/token`
 */
final class CreateUserTokenRequest extends BaseRequest
{
    protected Method $method = Method::POST;

    public function __construct(public readonly int $userId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/users/{$this->userId}/token";
    }
}
