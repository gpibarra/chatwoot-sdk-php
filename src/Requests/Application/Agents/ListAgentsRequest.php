<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Agents;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the agents of the account.
 *
 * `GET /api/v1/accounts/{accountId}/agents`
 */
final class ListAgentsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agents";
    }

    /**
     * @return list<Agent>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Agent::fromArray(...), ResponseData::list($response));
    }
}
