<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Messages;

use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Delete a message (its content is replaced and attachments removed).
 *
 * `DELETE /api/v1/accounts/{accountId}/conversations/{conversationId}/messages/{messageId}`
 */
final class DeleteMessageRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
        public readonly int $messageId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/messages/{$this->messageId}";
    }

    public function createDtoFromResponse(Response $response): Message
    {
        return Message::fromArray(ResponseData::object($response));
    }
}
