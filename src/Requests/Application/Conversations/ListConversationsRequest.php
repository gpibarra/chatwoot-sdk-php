<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List conversations (25 per page).
 *
 * `GET /api/v1/accounts/{accountId}/conversations`
 */
final class ListConversationsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        array $query = [],
    ) {
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations";
    }

    /**
     * @return Page<Conversation>
     */
    public function createDtoFromResponse(Response $response): Page
    {
        $meta = ResponseData::dig(ResponseData::decode($response), ['data', 'meta']);

        return new Page(
            items: array_map(Conversation::fromArray(...), ResponseData::list($response, 'data', 'payload')),
            meta: is_array($meta) ? $meta : [],
            raw: ResponseData::decode($response),
        );
    }
}
