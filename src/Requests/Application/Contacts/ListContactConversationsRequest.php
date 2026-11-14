<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Contacts;

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the conversations of a contact.
 *
 * `GET /api/v1/accounts/{accountId}/contacts/{contactId}/conversations`
 */
final class ListContactConversationsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $contactId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/contacts/{$this->contactId}/conversations";
    }

    /**
     * @return list<Conversation>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Conversation::fromArray(...), ResponseData::list($response, 'payload'));
    }
}
