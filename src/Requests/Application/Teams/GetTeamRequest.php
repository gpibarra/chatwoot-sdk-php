<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Teams;

use GPIbarra\ChatwootSdk\Data\Team;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get a team.
 *
 * `GET /api/v1/accounts/{accountId}/teams/{teamId}`
 */
final class GetTeamRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $teamId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/teams/{$this->teamId}";
    }

    public function createDtoFromResponse(Response $response): Team
    {
        return Team::fromArray(ResponseData::object($response));
    }
}
