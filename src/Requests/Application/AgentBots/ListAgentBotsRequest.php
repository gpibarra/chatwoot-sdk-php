<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AgentBots;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the agent bots available to the account (account bots and global bots).
 *
 * `GET /api/v1/accounts/{accountId}/agent_bots`
 */
final class ListAgentBotsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agent_bots";
    }

    /**
     * @return list<AgentBot>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(AgentBot::fromArray(...), ResponseData::list($response));
    }
}
