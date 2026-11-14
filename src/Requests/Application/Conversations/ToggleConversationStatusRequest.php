<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Change the status of a conversation.
 *
 * `POST /api/v1/accounts/{accountId}/conversations/{conversationId}/toggle_status`
 */
final class ToggleConversationStatusRequest extends JsonBodyRequest
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
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/toggle_status";
    }
}
