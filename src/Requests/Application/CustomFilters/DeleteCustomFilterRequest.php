<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CustomFilters;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a saved custom filter.
 *
 * `DELETE /api/v1/accounts/{accountId}/custom_filters/{customFilterId}`
 */
final class DeleteCustomFilterRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $customFilterId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/custom_filters/{$this->customFilterId}";
    }
}
