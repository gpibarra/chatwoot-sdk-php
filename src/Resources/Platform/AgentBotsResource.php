<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Platform;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\Platform\AgentBots\CreateAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\AgentBots\DeleteAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\AgentBots\GetAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\AgentBots\ListAgentBotsRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\AgentBots\UpdateAgentBotRequest;
use GPIbarra\ChatwootSdk\Resources\Resource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Agent bots owned by the platform app. Bots created without `account_id` are global and listed in
 * every account.
 */
final class AgentBotsResource extends Resource
{
    /**
     * @return list<AgentBot>
     */
    public function list(): array
    {
        $request = new ListAgentBotsRequest;

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $extra  `avatar_url`.
     */
    public function create(
        string $name,
        ?string $outgoingUrl = null,
        ?string $description = null,
        ?int $accountId = null,
        array $extra = [],
    ): AgentBot {
        $request = new CreateAgentBotRequest(Payload::merge([
            'name' => $name,
            'outgoing_url' => $outgoingUrl,
            'description' => $description,
            'account_id' => $accountId,
        ], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $agentBotId): AgentBot
    {
        $request = new GetAgentBotRequest($agentBotId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `name`, `description`, `outgoing_url`, `account_id`, `avatar_url`.
     */
    public function update(int $agentBotId, array $attributes): AgentBot
    {
        $request = new UpdateAgentBotRequest($agentBotId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $agentBotId): void
    {
        $this->sendForNothing(new DeleteAgentBotRequest($agentBotId));
    }
}
