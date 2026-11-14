<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources;

use Saloon\Http\Connector;

/**
 * Base for resources that live under `/api/v1/accounts/{accountId}`.
 */
abstract class AccountScopedResource extends Resource
{
    public function __construct(Connector $connector, protected readonly int $accountId)
    {
        parent::__construct($connector);
    }

    public function accountId(): int
    {
        return $this->accountId;
    }
}
