<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Campaigns;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Create a campaign.
 *
 * `POST /api/v1/accounts/{accountId}/campaigns`
 */
final class CreateCampaignRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/campaigns";
    }
}
