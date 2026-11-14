<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CustomFilters;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get a saved custom filter.
 *
 * `GET /api/v1/accounts/{accountId}/custom_filters/{customFilterId}`
 */
final class GetCustomFilterRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $customFilterId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/custom_filters/{$this->customFilterId}";
    }
}
