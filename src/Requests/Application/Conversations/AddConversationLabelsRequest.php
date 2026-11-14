<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Set the labels of a conversation (replaces the current list).
 *
 * `POST /api/v1/accounts/{accountId}/conversations/{conversationId}/labels`
 */
final class AddConversationLabelsRequest extends JsonBodyRequest
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
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/labels";
    }
}
