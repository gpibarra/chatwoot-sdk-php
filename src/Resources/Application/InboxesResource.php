<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Data\Inbox;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\AddInboxMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\CreateInboxRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\DeleteInboxRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\GetBrandedEmailLayoutRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\GetInboxAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\GetInboxRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\ListInboxesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\ListInboxMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\ListInboxMessageTemplatesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\RemoveInboxMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\SetInboxAgentBotRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\UpdateBrandedEmailLayoutRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\UpdateInboxMembersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\UpdateInboxRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Cast;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Inboxes, inbox members and the inbox agent bot.
 */
final class InboxesResource extends AccountScopedResource
{
    /**
     * @return list<Inbox>
     */
    public function list(): array
    {
        $request = new ListInboxesRequest($this->accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $inboxId): Inbox
    {
        $request = new GetInboxRequest($this->accountId, $inboxId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Creates an inbox and its channel.
     *
     * A WhatsApp Cloud channel, for example:
     * `['type' => 'whatsapp', 'phone_number' => '+5491100000000', 'provider' => 'whatsapp_cloud',
     *   'provider_config' => ['api_key' => ..., 'phone_number_id' => ..., 'business_account_id' => ...,
     *   'app_secret' => ..., 'webhook_verify_token' => ..., 'source' => 'manual_setup_v2']]`.
     * Allowed channel types: `web_widget`, `api`, `email`, `line`, `telegram`, `whatsapp`, `sms`.
     *
     * @param  array<string, mixed>  $channel  The `channel` object; `type` selects the channel class.
     * @param  array<string, mixed>  $extra  Other inbox attributes (`greeting_enabled`, `enable_auto_assignment`, ...).
     */
    public function create(string $name, array $channel, array $extra = []): Inbox
    {
        $request = new CreateInboxRequest($this->accountId, Payload::merge(['name' => $name, 'channel' => $channel], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  Inbox attributes; channel attributes go under `channel`.
     */
    public function update(int $inboxId, array $attributes): Inbox
    {
        $request = new UpdateInboxRequest($this->accountId, $inboxId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Chatwoot answers immediately and deletes the inbox in a background job.
     */
    public function delete(int $inboxId): void
    {
        $this->sendForNothing(new DeleteInboxRequest($this->accountId, $inboxId));
    }

    /**
     * Connects an agent bot to the inbox, or disconnects the current one when `$agentBotId` is null.
     */
    public function setAgentBot(int $inboxId, ?int $agentBotId): void
    {
        $this->sendForNothing(new SetInboxAgentBotRequest($this->accountId, $inboxId, ['agent_bot' => $agentBotId]));
    }

    /**
     * The agent bot connected to the inbox, or null when there is none.
     */
    public function agentBot(int $inboxId): ?AgentBot
    {
        $bot = Cast::nullableObject($this->sendForArray(new GetInboxAgentBotRequest($this->accountId, $inboxId)), 'agent_bot');

        return $bot !== null && isset($bot['id']) ? AgentBot::fromArray($bot) : null;
    }

    /**
     * WhatsApp message templates synced for the inbox.
     *
     * @return array<array-key, mixed>
     */
    public function messageTemplates(int $inboxId, ?string $name = null): array
    {
        return $this->sendForArray(new ListInboxMessageTemplatesRequest($this->accountId, $inboxId, Payload::filter(['name' => $name])));
    }

    /**
     * @return list<Agent>
     */
    public function members(int $inboxId): array
    {
        $request = new ListInboxMembersRequest($this->accountId, $inboxId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<int>  $userIds
     * @return list<Agent> The inbox members after the change.
     */
    public function addMembers(int $inboxId, array $userIds): array
    {
        $request = new AddInboxMembersRequest($this->accountId, ['inbox_id' => $inboxId, 'user_ids' => $userIds]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Replaces the inbox members with exactly the given users.
     *
     * @param  list<int>  $userIds
     * @return list<Agent> The inbox members after the change.
     */
    public function updateMembers(int $inboxId, array $userIds): array
    {
        $request = new UpdateInboxMembersRequest($this->accountId, ['inbox_id' => $inboxId, 'user_ids' => $userIds]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<int>  $userIds
     */
    public function removeMembers(int $inboxId, array $userIds): void
    {
        $this->sendForNothing(new RemoveInboxMembersRequest($this->accountId, ['inbox_id' => $inboxId, 'user_ids' => $userIds]));
    }

    /**
     * The account-level branded email layout (Liquid HTML) used by email inboxes.
     */
    public function brandedEmailLayout(): ?string
    {
        return Cast::nullableString($this->sendForArray(new GetBrandedEmailLayoutRequest($this->accountId)), 'branded_email_layout');
    }

    /**
     * Sets, or clears with null, the account-level branded email layout.
     */
    public function updateBrandedEmailLayout(?string $layout): ?string
    {
        $response = $this->sendForArray(new UpdateBrandedEmailLayoutRequest($this->accountId, ['branded_email_layout' => $layout]));

        return Cast::nullableString($response, 'branded_email_layout');
    }
}
