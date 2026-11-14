<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get a conversation.
 *
 * `GET /api/v1/accounts/{accountId}/conversations/{conversationId}`
 */
final class GetConversationRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}";
    }

    public function createDtoFromResponse(Response $response): Conversation
    {
        return Conversation::fromArray(ResponseData::object($response));
    }
}
