<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Teams;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Add agents to a team.
 *
 * `POST /api/v1/accounts/{accountId}/teams/{teamId}/team_members`
 */
final class AddTeamMembersRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $teamId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/teams/{$this->teamId}/team_members";
    }

    /**
     * @return list<Agent>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Agent::fromArray(...), ResponseData::list($response));
    }
}
