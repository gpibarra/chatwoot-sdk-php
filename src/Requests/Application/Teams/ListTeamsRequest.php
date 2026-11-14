<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Teams;

use GPIbarra\ChatwootSdk\Data\Team;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the account teams.
 *
 * `GET /api/v1/accounts/{accountId}/teams`
 */
final class ListTeamsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/teams";
    }

    /**
     * @return list<Team>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Team::fromArray(...), ResponseData::list($response));
    }
}
