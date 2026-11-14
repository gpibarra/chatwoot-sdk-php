<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AgentBots;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update an account agent bot.
 *
 * `PATCH /api/v1/accounts/{accountId}/agent_bots/{agentBotId}`
 */
final class UpdateAgentBotRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $agentBotId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agent_bots/{$this->agentBotId}";
    }

    public function createDtoFromResponse(Response $response): AgentBot
    {
        return AgentBot::fromArray(ResponseData::object($response));
    }
}
