<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Platform;

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Requests\Platform\Accounts\CreateAccountRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Accounts\DeleteAccountRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Accounts\GetAccountRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Accounts\ListAccountsRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Accounts\UpdateAccountRequest;
use GPIbarra\ChatwootSdk\Resources\Resource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Accounts managed by the platform app.
 */
final class AccountsResource extends Resource
{
    /**
     * Accounts the platform app created or was granted (not in the swagger; `Platform::Api::V1::AccountsController#index`).
     *
     * @return list<Account>
     */
    public function list(): array
    {
        $request = new ListAccountsRequest;

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $extra  `locale`, `domain`, `support_email`, `status`, `limits`, `custom_attributes`,
     *                                       `features` (map of feature name => bool).
     */
    public function create(string $name, array $extra = []): Account
    {
        $request = new CreateAccountRequest(Payload::merge(['name' => $name], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $accountId): Account
    {
        $request = new GetAccountRequest($accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(int $accountId, array $attributes): Account
    {
        $request = new UpdateAccountRequest($accountId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Chatwoot answers immediately and deletes the account in a background job.
     */
    public function delete(int $accountId): void
    {
        $this->sendForNothing(new DeleteAccountRequest($accountId));
    }
}
