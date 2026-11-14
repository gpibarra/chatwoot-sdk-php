<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\Accounts;

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create an account.
 *
 * `POST /platform/api/v1/accounts`
 */
final class CreateAccountRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return '/platform/api/v1/accounts';
    }

    public function createDtoFromResponse(Response $response): Account
    {
        return Account::fromArray(ResponseData::object($response));
    }
}
