<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the agents of an inbox.
 *
 * `GET /api/v1/accounts/{accountId}/inbox_members/{inboxId}`
 */
final class ListInboxMembersRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $inboxId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inbox_members/{$this->inboxId}";
    }

    /**
     * @return list<Agent>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Agent::fromArray(...), ResponseData::list($response, 'payload'));
    }
}
