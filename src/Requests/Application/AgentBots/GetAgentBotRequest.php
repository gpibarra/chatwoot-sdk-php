<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AgentBots;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get an agent bot.
 *
 * `GET /api/v1/accounts/{accountId}/agent_bots/{agentBotId}`
 */
final class GetAgentBotRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $agentBotId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agent_bots/{$this->agentBotId}";
    }

    public function createDtoFromResponse(Response $response): AgentBot
    {
        return AgentBot::fromArray(ResponseData::object($response));
    }
}
