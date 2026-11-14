<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Data\Inbox;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the inboxes the token can access.
 *
 * `GET /api/v1/accounts/{accountId}/inboxes`
 */
final class ListInboxesRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inboxes";
    }

    /**
     * @return list<Inbox>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Inbox::fromArray(...), ResponseData::list($response, 'payload'));
    }
}
