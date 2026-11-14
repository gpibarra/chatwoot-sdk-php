<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Webhooks;

use GPIbarra\ChatwootSdk\Data\Webhook;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create a webhook.
 *
 * `POST /api/v1/accounts/{accountId}/webhooks`
 */
final class CreateWebhookRequest extends JsonBodyRequest
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
        return "/api/v1/accounts/{$this->accountId}/webhooks";
    }

    public function createDtoFromResponse(Response $response): Webhook
    {
        return Webhook::fromArray(ResponseData::object($response, 'payload', 'webhook'));
    }
}
