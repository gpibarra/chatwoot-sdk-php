<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\AccountUsers;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List the users of an account.
 *
 * `GET /platform/api/v1/accounts/{accountId}/account_users`
 */
final class ListAccountUsersRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/accounts/{$this->accountId}/account_users";
    }
}
