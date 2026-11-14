<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\AccountUsers;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Add a user to an account (or change their role).
 *
 * `POST /platform/api/v1/accounts/{accountId}/account_users`
 */
final class CreateAccountUserRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/accounts/{$this->accountId}/account_users";
    }
}
