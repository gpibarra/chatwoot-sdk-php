<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Platform\AgentBots;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the agent bots of the platform app.
 *
 * `GET /platform/api/v1/agent_bots`
 */
final class ListAgentBotsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/platform/api/v1/agent_bots';
    }

    /**
     * @return list<AgentBot>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(AgentBot::fromArray(...), ResponseData::list($response));
    }
}
