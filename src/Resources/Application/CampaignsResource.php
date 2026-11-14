<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\Campaigns\CreateCampaignRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Campaigns\DeleteCampaignRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Campaigns\GetCampaignRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Campaigns\ListCampaignsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Campaigns\UpdateCampaignRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;

/**
 * Campaigns. Bodies are sent under the `campaign` root key the controller requires; campaign IDs are
 * display IDs.
 */
final class CampaignsResource extends AccountScopedResource
{
    /**
     * @return array<array-key, mixed>
     */
    public function list(): array
    {
        return $this->sendForArray(new ListCampaignsRequest($this->accountId));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function get(int $campaignId): array
    {
        return $this->sendForArray(new GetCampaignRequest($this->accountId, $campaignId));
    }

    /**
     * @param  array<string, mixed>  $attributes  `title`, `message`, `inbox_id`, `sender_id`, `enabled`, `scheduled_at`,
     *                                            `audience`, `trigger_rules`, `template_params`, ...
     * @return array<array-key, mixed>
     */
    public function create(array $attributes): array
    {
        return $this->sendForArray(new CreateCampaignRequest($this->accountId, ['campaign' => $attributes]));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function update(int $campaignId, array $attributes): array
    {
        return $this->sendForArray(new UpdateCampaignRequest($this->accountId, $campaignId, ['campaign' => $attributes]));
    }

    public function delete(int $campaignId): void
    {
        $this->sendForNothing(new DeleteCampaignRequest($this->accountId, $campaignId));
    }
}
