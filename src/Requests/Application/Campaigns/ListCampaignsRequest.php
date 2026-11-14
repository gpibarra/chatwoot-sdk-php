<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Campaigns;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List campaigns.
 *
 * `GET /api/v1/accounts/{accountId}/campaigns`
 */
final class ListCampaignsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/campaigns";
    }
}
