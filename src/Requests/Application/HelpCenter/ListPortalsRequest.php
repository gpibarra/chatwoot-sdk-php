<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\HelpCenter;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List help center portals.
 *
 * `GET /api/v1/accounts/{accountId}/portals`
 */
final class ListPortalsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/portals";
    }
}
