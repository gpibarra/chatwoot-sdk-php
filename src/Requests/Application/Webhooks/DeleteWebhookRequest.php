<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Webhooks;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a webhook.
 *
 * `DELETE /api/v1/accounts/{accountId}/webhooks/{webhookId}`
 */
final class DeleteWebhookRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $webhookId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/webhooks/{$this->webhookId}";
    }
}
