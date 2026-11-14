<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AgentBots;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an account agent bot.
 *
 * `DELETE /api/v1/accounts/{accountId}/agent_bots/{agentBotId}`
 */
final class DeleteAgentBotRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $agentBotId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agent_bots/{$this->agentBotId}";
    }
}
