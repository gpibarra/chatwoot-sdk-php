<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Remove custom attributes from a conversation.
 *
 * `POST /api/v1/accounts/{accountId}/conversations/{conversationId}/destroy_custom_attributes`
 */
final class DestroyConversationCustomAttributesRequest extends JsonBodyRequest
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
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/destroy_custom_attributes";
    }
}
