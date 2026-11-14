<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Enums\AgentRole;
use GPIbarra\ChatwootSdk\Requests\Application\Agents\CreateAgentRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Agents\DeleteAgentRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Agents\ListAgentsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Agents\UpdateAgentRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Account agents. Bodies are sent under the `agent` root key the controller requires.
 */
final class AgentsResource extends AccountScopedResource
{
    /**
     * @return list<Agent>
     */
    public function list(): array
    {
        $request = new ListAgentsRequest($this->accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Invites a user to the account (creating the user when the email is unknown).
     *
     * @param  array<string, mixed>  $extra  `availability`, `auto_offline`.
     */
    public function create(string $name, string $email, AgentRole|string $role = AgentRole::Agent, array $extra = []): Agent
    {
        $request = new CreateAgentRequest($this->accountId, ['agent' => Payload::merge([
            'name' => $name,
            'email' => $email,
            'role' => $role instanceof AgentRole ? $role->value : $role,
        ], $extra)]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `role`, `availability`, `auto_offline`.
     */
    public function update(int $agentId, array $attributes): Agent
    {
        $request = new UpdateAgentRequest($this->accountId, $agentId, ['agent' => $attributes]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $agentId): void
    {
        $this->sendForNothing(new DeleteAgentRequest($this->accountId, $agentId));
    }
}
