<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Accounts;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an account (performed asynchronously by Chatwoot).
 *
 * `DELETE /platform/api/v1/accounts/{accountId}`
 */
final class DeleteAccountRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/accounts/{$this->accountId}";
    }
}
