<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Users;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get a single sign-on login URL for a user.
 *
 * `GET /platform/api/v1/users/{userId}/login`
 */
final class GetUserLoginUrlRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $userId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/users/{$this->userId}/login";
    }
}
