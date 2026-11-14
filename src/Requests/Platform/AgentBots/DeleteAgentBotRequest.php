<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\AgentBots;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an agent bot.
 *
 * `DELETE /platform/api/v1/agent_bots/{agentBotId}`
 */
final class DeleteAgentBotRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(public readonly int $agentBotId) {}

    public function resolveEndpoint(): string
    {
        return "/platform/api/v1/agent_bots/{$this->agentBotId}";
    }
}
