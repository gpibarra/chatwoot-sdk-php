<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Accounts;

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get an account.
 *
 * `GET /platform/api/v1/accounts/{accountId}`
 */
final class GetAccountRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/accounts/{$this->accountId}";
    }

    public function createDtoFromResponse(Response $response): Account
    {
        return Account::fromArray(ResponseData::object($response));
    }
}
