<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Requests\Application\Accounts\GetAccountRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Accounts\UpdateAccountRequest;
use GPIbarra\ChatwootSdk\Resources\Resource;

/**
 * Accounts the token's user belongs to.
 */
final class AccountsResource extends Resource
{
    public function get(int $accountId): Account
    {
        $request = new GetAccountRequest($accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `name`, `locale`, `domain`, `support_email`, settings such as
     *                                            `auto_resolve_after`, and custom attributes such as `industry`.
     */
    public function update(int $accountId, array $attributes): Account
    {
        $request = new UpdateAccountRequest($accountId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }
}
