<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Messages;

use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update the delivery status of a message (API inboxes only).
 *
 * `PATCH /api/v1/accounts/{accountId}/conversations/{conversationId}/messages/{messageId}`
 */
final class UpdateMessageRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
        public readonly int $messageId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/messages/{$this->messageId}";
    }

    public function createDtoFromResponse(Response $response): Message
    {
        return Message::fromArray(ResponseData::object($response));
    }
}
