<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Users;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a user (performed asynchronously by Chatwoot).
 *
 * `DELETE /platform/api/v1/users/{userId}`
 */
final class DeleteUserRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(public readonly int $userId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/users/{$this->userId}";
    }
}
