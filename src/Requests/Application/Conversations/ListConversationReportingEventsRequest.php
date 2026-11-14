<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Conversations;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List the reporting events of a conversation (Enterprise edition).
 *
 * `GET /api/v1/accounts/{accountId}/conversations/{conversationId}/reporting_events`
 */
final class ListConversationReportingEventsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/reporting_events";
    }
}
