<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Mark a conversation as seen by the current agent.
 *
 * `POST /api/v1/accounts/{accountId}/conversations/{conversationId}/update_last_seen`
 */
final class UpdateConversationLastSeenRequest extends BaseRequest
{
    protected Method $method = Method::POST;

    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/update_last_seen";
    }
}
