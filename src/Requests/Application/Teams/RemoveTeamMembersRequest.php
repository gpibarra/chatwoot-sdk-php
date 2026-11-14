<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Teams;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Remove agents from a team.
 *
 * `DELETE /api/v1/accounts/{accountId}/teams/{teamId}/team_members`
 */
final class RemoveTeamMembersRequest extends JsonBodyRequest
{
    protected Method $method = Method::DELETE;

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
}
