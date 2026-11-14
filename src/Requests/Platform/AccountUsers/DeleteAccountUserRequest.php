<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\AccountUsers;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Remove a user from an account.
 *
 * `DELETE /platform/api/v1/accounts/{accountId}/account_users`
 */
final class DeleteAccountUserRequest extends JsonBodyRequest
{
    protected Method $method = Method::DELETE;

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
