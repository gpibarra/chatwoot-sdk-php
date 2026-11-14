<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Messages;

use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create a message in a conversation.
 *
 * `POST /api/v1/accounts/{accountId}/conversations/{conversationId}/messages`
 */
final class CreateMessageRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/messages";
    }

    public function createDtoFromResponse(Response $response): Message
    {
        return Message::fromArray(ResponseData::object($response));
    }
}
