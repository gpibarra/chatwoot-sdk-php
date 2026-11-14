<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Data\Inbox;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update an inbox and its channel.
 *
 * `PATCH /api/v1/accounts/{accountId}/inboxes/{inboxId}`
 */
final class UpdateInboxRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $inboxId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inboxes/{$this->inboxId}";
    }

    public function createDtoFromResponse(Response $response): Inbox
    {
        return Inbox::fromArray(ResponseData::object($response));
    }
}
