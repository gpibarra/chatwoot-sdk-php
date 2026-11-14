<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get the agent bot connected to an inbox.
 *
 * `GET /api/v1/accounts/{accountId}/inboxes/{inboxId}/agent_bot`
 */
final class GetInboxAgentBotRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $inboxId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inboxes/{$this->inboxId}/agent_bot";
    }
}
