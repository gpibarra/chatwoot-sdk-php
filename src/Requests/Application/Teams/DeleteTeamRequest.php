<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Teams;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a team.
 *
 * `DELETE /api/v1/accounts/{accountId}/teams/{teamId}`
 */
final class DeleteTeamRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $teamId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/teams/{$this->teamId}";
    }
}
