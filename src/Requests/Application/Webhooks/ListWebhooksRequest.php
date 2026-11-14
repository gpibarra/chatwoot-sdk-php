<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Webhooks;

use GPIbarra\ChatwootSdk\Data\Webhook;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the account webhooks.
 *
 * `GET /api/v1/accounts/{accountId}/webhooks`
 */
final class ListWebhooksRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/webhooks";
    }

    /**
     * @return list<Webhook>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Webhook::fromArray(...), ResponseData::list($response, 'payload', 'webhooks'));
    }
}
