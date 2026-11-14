<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Enums\ConversationPriority;
use GPIbarra\ChatwootSdk\Enums\ConversationStatus;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\AddConversationLabelsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\AssignConversationRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\CreateConversationRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\DeleteConversationRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\DestroyConversationCustomAttributesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\FilterConversationsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\GetConversationMetaRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\GetConversationRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\ListConversationLabelsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\ListConversationReportingEventsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\ListConversationsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\ToggleConversationPriorityRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\ToggleConversationStatusRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\ToggleConversationTypingStatusRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\UpdateConversationCustomAttributesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\UpdateConversationLastSeenRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Conversations\UpdateConversationRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Cast;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Conversations. Conversation IDs are the account-scoped display IDs.
 */
final class ConversationsResource extends AccountScopedResource
{
    /**
     * @param  array<string, mixed>  $query  `status`, `q`, `inbox_id`, `team_id`, `labels[]`.
     * @return array<array-key, mixed> `{meta: {mine_count, assigned_count, unassigned_count, all_count}}`.
     */
    public function meta(array $query = []): array
    {
        return $this->sendForArray(new GetConversationMetaRequest($this->accountId, $query));
    }

    /**
     * @param  string|null  $assigneeType  `me`, `unassigned`, `all` or `assigned`.
     * @param  array<string, mixed>  $query  `q`, `inbox_id`, `team_id`, `labels[]`, `sort_by`, ...
     * @return Page<Conversation> `meta` holds the counts.
     */
    public function list(
        ConversationStatus|string|null $status = null,
        ?string $assigneeType = null,
        ?int $page = null,
        array $query = [],
    ): Page {
        $request = new ListConversationsRequest($this->accountId, Payload::merge([
            'status' => $status instanceof ConversationStatus ? $status->value : $status,
            'assignee_type' => $assigneeType,
            'page' => $page,
        ], $query));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  string|null  $sourceId  Contact inbox source ID; with `$contactId` Chatwoot can resolve it instead.
     * @param  array<string, mixed>  $extra  `status`, `assignee_id`, `team_id`, `additional_attributes`, `custom_attributes`,
     *                                       `snoozed_until`, `message` (`{content, template_params}`).
     */
    public function create(int $inboxId, ?int $contactId = null, ?string $sourceId = null, array $extra = []): Conversation
    {
        $request = new CreateConversationRequest($this->accountId, Payload::merge([
            'inbox_id' => $inboxId,
            'contact_id' => $contactId,
            'source_id' => $sourceId,
        ], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<array<string, mixed>>  $payload  Filter conditions: `{attribute_key, filter_operator, values, query_operator}`.
     * @return Page<Conversation>
     */
    public function filter(array $payload, ?int $page = null): Page
    {
        $request = new FilterConversationsRequest($this->accountId, ['payload' => $payload], Payload::filter(['page' => $page]));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $conversationId): Conversation
    {
        $request = new GetConversationRequest($this->accountId, $conversationId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `priority`, `sla_policy_id`.
     */
    public function update(int $conversationId, array $attributes): Conversation
    {
        $request = new UpdateConversationRequest($this->accountId, $conversationId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Requires an administrator token.
     */
    public function delete(int $conversationId): void
    {
        $this->sendForNothing(new DeleteConversationRequest($this->accountId, $conversationId));
    }

    /**
     * @param  int|null  $snoozedUntil  Unix timestamp, for `snoozed`.
     * @return array<array-key, mixed> `{meta: {}, payload: {success, conversation_id, current_status, snoozed_until}}`.
     */
    public function toggleStatus(int $conversationId, ConversationStatus|string $status, ?int $snoozedUntil = null): array
    {
        return $this->sendForArray(new ToggleConversationStatusRequest($this->accountId, $conversationId, Payload::filter([
            'status' => $status instanceof ConversationStatus ? $status->value : $status,
            'snoozed_until' => $snoozedUntil,
        ])));
    }

    /**
     * @param  ConversationPriority|string|null  $priority  null clears the priority.
     */
    public function togglePriority(int $conversationId, ConversationPriority|string|null $priority): void
    {
        $this->sendForNothing(new ToggleConversationPriorityRequest($this->accountId, $conversationId, [
            'priority' => $priority instanceof ConversationPriority ? $priority->value : $priority,
        ]));
    }

    /**
     * @param  string  $typingStatus  `on` or `off`.
     */
    public function toggleTypingStatus(int $conversationId, string $typingStatus, ?bool $isPrivate = null): void
    {
        $this->sendForNothing(new ToggleConversationTypingStatusRequest($this->accountId, $conversationId, Payload::filter([
            'typing_status' => $typingStatus,
            'is_private' => $isPrivate,
        ])));
    }

    /**
     * Chatwoot throttles this call when there are no unread messages, so the body may be empty.
     */
    public function updateLastSeen(int $conversationId): void
    {
        $this->sendForNothing(new UpdateConversationLastSeenRequest($this->accountId, $conversationId));
    }

    /**
     * @param  array<string, mixed>  $customAttributes
     * @param  bool  $merge  Merge into the existing attributes instead of replacing them (newer Chatwoot versions).
     * @return array<array-key, mixed> The conversation's custom attributes after the change.
     */
    public function setCustomAttributes(int $conversationId, array $customAttributes, bool $merge = false): array
    {
        $body = ['custom_attributes' => $customAttributes];

        if ($merge) {
            $body['merge'] = true;
        }

        $response = $this->sendForArray(new UpdateConversationCustomAttributesRequest($this->accountId, $conversationId, $body));

        return Cast::array($response, 'custom_attributes');
    }

    /**
     * @param  list<string>  $keys
     * @return array<array-key, mixed> The conversation's custom attributes after the change.
     */
    public function destroyCustomAttributes(int $conversationId, array $keys): array
    {
        $response = $this->sendForArray(new DestroyConversationCustomAttributesRequest($this->accountId, $conversationId, ['custom_attributes' => $keys]));

        return Cast::array($response, 'custom_attributes');
    }

    /**
     * Assigns the conversation to an agent (or unassigns it with null).
     *
     * @return array<array-key, mixed> The assigned agent, or an empty array when unassigned.
     */
    public function assignAgent(int $conversationId, ?int $assigneeId): array
    {
        return $this->assign($conversationId, ['assignee_id' => $assigneeId]);
    }

    /**
     * @return array<array-key, mixed> The assigned agent bot.
     */
    public function assignAgentBot(int $conversationId, int $agentBotId): array
    {
        return $this->assign($conversationId, ['assignee_id' => $agentBotId, 'assignee_type' => 'AgentBot']);
    }

    /**
     * Assigns the conversation to a team (or removes the team with null or 0).
     *
     * @return array<array-key, mixed> The team, or an empty array when removed.
     */
    public function assignTeam(int $conversationId, ?int $teamId): array
    {
        return $this->assign($conversationId, ['team_id' => $teamId ?? 0]);
    }

    /**
     * Raw assignment call: `assignee_id` (+ `assignee_type`) takes precedence over `team_id`.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function assign(int $conversationId, array $attributes): array
    {
        return $this->sendForArray(new AssignConversationRequest($this->accountId, $conversationId, $attributes));
    }

    /**
     * @return list<string>
     */
    public function labels(int $conversationId): array
    {
        return Cast::listOfStrings($this->sendForArray(new ListConversationLabelsRequest($this->accountId, $conversationId)), 'payload');
    }

    /**
     * Replaces the conversation's labels with the given list.
     *
     * @param  list<string>  $labels
     * @return list<string> The labels after the change.
     */
    public function setLabels(int $conversationId, array $labels): array
    {
        $response = $this->sendForArray(new AddConversationLabelsRequest($this->accountId, $conversationId, ['labels' => $labels]));

        return Cast::listOfStrings($response, 'payload');
    }

    /**
     * Enterprise edition only.
     *
     * @return array<array-key, mixed>
     */
    public function reportingEvents(int $conversationId): array
    {
        return $this->sendForArray(new ListConversationReportingEventsRequest($this->accountId, $conversationId));
    }
}
