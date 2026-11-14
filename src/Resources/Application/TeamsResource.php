<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Data\Team;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\AddTeamMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\CreateTeamRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\DeleteTeamRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\GetTeamRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\ListTeamMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\ListTeamsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\RemoveTeamMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\UpdateTeamMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Teams\UpdateTeamRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Teams and team members. Team bodies are sent under the `team` root key the controller requires.
 */
final class TeamsResource extends AccountScopedResource
{
    /**
     * @return list<Team>
     */
    public function list(): array
    {
        $request = new ListTeamsRequest($this->accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $teamId): Team
    {
        $request = new GetTeamRequest($this->accountId, $teamId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $extra  `icon`, `icon_color`.
     */
    public function create(string $name, ?string $description = null, ?bool $allowAutoAssign = null, array $extra = []): Team
    {
        $request = new CreateTeamRequest($this->accountId, ['team' => Payload::merge([
            'name' => $name,
            'description' => $description,
            'allow_auto_assign' => $allowAutoAssign,
        ], $extra)]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `name`, `description`, `allow_auto_assign`, `icon`, `icon_color`.
     */
    public function update(int $teamId, array $attributes): Team
    {
        $request = new UpdateTeamRequest($this->accountId, $teamId, ['team' => $attributes]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $teamId): void
    {
        $this->sendForNothing(new DeleteTeamRequest($this->accountId, $teamId));
    }

    /**
     * @return list<Agent>
     */
    public function members(int $teamId): array
    {
        $request = new ListTeamMembersRequest($this->accountId, $teamId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<int>  $userIds
     * @return list<Agent> The members that were added.
     */
    public function addMembers(int $teamId, array $userIds): array
    {
        $request = new AddTeamMembersRequest($this->accountId, $teamId, ['user_ids' => $userIds]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Replaces the team members with exactly the given users.
     *
     * @param  list<int>  $userIds
     * @return list<Agent>
     */
    public function updateMembers(int $teamId, array $userIds): array
    {
        $request = new UpdateTeamMembersRequest($this->accountId, $teamId, ['user_ids' => $userIds]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<int>  $userIds
     */
    public function removeMembers(int $teamId, array $userIds): void
    {
        $this->sendForNothing(new RemoveTeamMembersRequest($this->accountId, $teamId, ['user_ids' => $userIds]));
    }
}
