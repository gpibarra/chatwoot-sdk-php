<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Messages;

use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the messages of a conversation (20 at a time, paginated with `before`/`after`).
 *
 * `GET /api/v1/accounts/{accountId}/conversations/{conversationId}/messages`
 */
final class ListMessagesRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
        array $query = [],
    ) {
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/messages";
    }

    /**
     * @return Page<Message>
     */
    public function createDtoFromResponse(Response $response): Page
    {
        $meta = ResponseData::dig(ResponseData::decode($response), ['meta']);

        return new Page(
            items: array_map(Message::fromArray(...), ResponseData::list($response, 'payload')),
            meta: is_array($meta) ? $meta : [],
            raw: ResponseData::decode($response),
        );
    }
}
