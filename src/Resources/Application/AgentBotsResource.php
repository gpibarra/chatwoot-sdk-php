<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Requests\Application\AgentBots\CreateAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AgentBots\DeleteAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AgentBots\GetAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AgentBots\ListAgentBotsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AgentBots\UpdateAgentBotRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Account agent bots. Listing also returns global bots created through the Platform API; only bots
 * owned by the account can be updated or deleted here.
 */
final class AgentBotsResource extends AccountScopedResource
{
    /**
     * @return list<AgentBot>
     */
    public function list(): array
    {
        $request = new ListAgentBotsRequest($this->accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $agentBotId): AgentBot
    {
        $request = new GetAgentBotRequest($this->accountId, $agentBotId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  string|null  $outgoingUrl  Webhook URL that receives the bot's events.
     * @param  array<string, mixed>  $extra  `avatar_url`, `bot_type`, `bot_config`, ...
     */
    public function create(string $name, ?string $outgoingUrl = null, ?string $description = null, array $extra = []): AgentBot
    {
        $request = new CreateAgentBotRequest($this->accountId, Payload::merge([
            'name' => $name,
            'outgoing_url' => $outgoingUrl,
            'description' => $description,
        ], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `name`, `description`, `outgoing_url`, `avatar_url`, `bot_type`, `bot_config`.
     */
    public function update(int $agentBotId, array $attributes): AgentBot
    {
        $request = new UpdateAgentBotRequest($this->accountId, $agentBotId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $agentBotId): void
    {
        $this->sendForNothing(new DeleteAgentBotRequest($this->accountId, $agentBotId));
    }
}
