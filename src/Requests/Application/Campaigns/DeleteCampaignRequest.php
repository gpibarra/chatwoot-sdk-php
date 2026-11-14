<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Campaigns;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a campaign.
 *
 * `DELETE /api/v1/accounts/{accountId}/campaigns/{campaignId}`
 */
final class DeleteCampaignRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $campaignId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/campaigns/{$this->campaignId}";
    }
}
