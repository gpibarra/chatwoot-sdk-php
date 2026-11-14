<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Campaigns;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get a campaign (by its display ID).
 *
 * `GET /api/v1/accounts/{accountId}/campaigns/{campaignId}`
 */
final class GetCampaignRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $campaignId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/campaigns/{$this->campaignId}";
    }
}
