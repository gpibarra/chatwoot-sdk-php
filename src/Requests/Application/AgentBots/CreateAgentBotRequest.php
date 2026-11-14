<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AgentBots;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create an account agent bot.
 *
 * `POST /api/v1/accounts/{accountId}/agent_bots`
 */
final class CreateAgentBotRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agent_bots";
    }

    public function createDtoFromResponse(Response $response): AgentBot
    {
        return AgentBot::fromArray(ResponseData::object($response));
    }
}
