<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Webhooks;

use GPIbarra\ChatwootSdk\Data\Webhook;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update a webhook.
 *
 * `PATCH /api/v1/accounts/{accountId}/webhooks/{webhookId}`
 */
final class UpdateWebhookRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $webhookId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/webhooks/{$this->webhookId}";
    }

    public function createDtoFromResponse(Response $response): Webhook
    {
        return Webhook::fromArray(ResponseData::object($response, 'payload', 'webhook'));
    }
}
