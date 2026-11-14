<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Campaigns;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Update a campaign.
 *
 * `PATCH /api/v1/accounts/{accountId}/campaigns/{campaignId}`
 */
final class UpdateCampaignRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $campaignId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/campaigns/{$this->campaignId}";
    }
}
