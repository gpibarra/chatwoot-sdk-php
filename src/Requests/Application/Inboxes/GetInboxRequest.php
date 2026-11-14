<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Data\Inbox;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get an inbox.
 *
 * `GET /api/v1/accounts/{accountId}/inboxes/{inboxId}`
 */
final class GetInboxRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $inboxId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inboxes/{$this->inboxId}";
    }

    public function createDtoFromResponse(Response $response): Inbox
    {
        return Inbox::fromArray(ResponseData::object($response));
    }
}
