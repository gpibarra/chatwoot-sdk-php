<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Accounts;

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the accounts the platform app has access to.
 *
 * `GET /platform/api/v1/accounts`
 */
final class ListAccountsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/platform/api/v1/accounts';
    }

    /**
     * @return list<Account>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Account::fromArray(...), ResponseData::list($response));
    }
}
