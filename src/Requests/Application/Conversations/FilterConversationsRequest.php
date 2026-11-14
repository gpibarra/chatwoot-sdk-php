<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Filter conversations with a filter payload.
 *
 * `POST /api/v1/accounts/{accountId}/conversations/filter`
 */
final class FilterConversationsRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
        array $query = [],
    ) {
        $this->payload = $payload;
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/filter";
    }

    /**
     * @return Page<Conversation>
     */
    public function createDtoFromResponse(Response $response): Page
    {
        $meta = ResponseData::dig(ResponseData::decode($response), ['meta']);

        return new Page(
            items: array_map(Conversation::fromArray(...), ResponseData::list($response, 'payload')),
            meta: is_array($meta) ? $meta : [],
            raw: ResponseData::decode($response),
        );
    }
}
