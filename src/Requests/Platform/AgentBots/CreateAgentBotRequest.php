<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\AgentBots;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create an agent bot.
 *
 * `POST /platform/api/v1/agent_bots`
 */
final class CreateAgentBotRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return '/platform/api/v1/agent_bots';
    }

    public function createDtoFromResponse(Response $response): AgentBot
    {
        return AgentBot::fromArray(ResponseData::object($response));
    }
}
