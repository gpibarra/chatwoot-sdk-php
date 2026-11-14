<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Enums\ConversationPriority;
use GPIbarra\ChatwootSdk\Enums\ConversationStatus;

it('maps a conversation', function (): void {
    $mock = mockClient(cwFixture('conversation'));

    $conversation = applicationApi($mock)->conversations(1)->get(15);

    assertSent($mock, 'GET', '/api/v1/accounts/1/conversations/15');
    expect($conversation)->toBeInstanceOf(Conversation::class)
        ->and($conversation->id)->toBe(15)
        ->and($conversation->uuid)->toBe('3f2a1c9e-0000-4000-8000-000000000015')
        ->and($conversation->accountId)->toBe(1)
        ->and($conversation->inboxId)->toBe(7)
        ->and($conversation->status)->toBe('open')
        ->and($conversation->priority)->toBe('high')
        ->and($conversation->contactId)->toBe(42)
        ->and($conversation->assigneeId)->toBe(5)
        ->and($conversation->teamId)->toBe(3)
        ->and($conversation->labels)->toBe(['vip'])
        ->and($conversation->customAttributes)->toBe(['order' => 'A-1'])
        ->and($conversation->unreadCount)->toBe(2)
        ->and($conversation->createdAt)->toBe(1_763_100_000)
        ->and($conversation->lastActivityAt)->toBe(1_763_100_600)
        ->and($conversation->meta['channel'])->toBe('Channel::Whatsapp');
});

it('lists conversations from the data.payload envelope', function (): void {
    // app/views/api/v1/accounts/conversations/index.json.jbuilder
    $meta = ['mine_count' => 1, 'assigned_count' => 1, 'unassigned_count' => 0, 'all_count' => 1];
    $mock = mockClient(['data' => ['meta' => $meta, 'payload' => [cwFixture('conversation')]]]);

    $page = applicationApi($mock)->conversations(1)->list(ConversationStatus::Open, 'all', 2, ['inbox_id' => 7]);

    assertSent($mock, 'GET', '/api/v1/accounts/1/conversations', ['inbox_id' => 7, 'status' => 'open', 'assignee_type' => 'all', 'page' => 2]);
    expect($page->meta)->toBe($meta)->and($page->items[0]->id)->toBe(15);
});

it('gets the conversation meta counts', function (): void {
    $mock = mockClient(['meta' => ['mine_count' => 0]]);

    expect(applicationApi($mock)->conversations(1)->meta(['status' => 'open']))->toBe(['meta' => ['mine_count' => 0]]);
    assertSent($mock, 'GET', '/api/v1/accounts/1/conversations/meta', ['status' => 'open']);
});

it('creates a conversation', function (): void {
    $mock = mockClient(cwFixture('conversation'));

    $conversation = applicationApi($mock)->conversations(1)->create(7, 42, null, ['message' => ['content' => 'Hi']]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations', body: [
        'message' => ['content' => 'Hi'],
        'inbox_id' => 7,
        'contact_id' => 42,
    ]);
    expect($conversation->id)->toBe(15);
});

it('filters conversations', function (): void {
    // app/views/api/v1/accounts/conversations/filter.json.jbuilder: {meta, payload} (no data envelope)
    $mock = mockClient(['meta' => ['all_count' => 1], 'payload' => [cwFixture('conversation')]]);
    $conditions = [['attribute_key' => 'status', 'filter_operator' => 'equal_to', 'values' => ['open']]];

    $page = applicationApi($mock)->conversations(1)->filter($conditions, 1);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/filter', ['page' => 1], ['payload' => $conditions]);
    expect($page->items[0]->id)->toBe(15)->and($page->meta)->toBe(['all_count' => 1]);
});

it('updates a conversation', function (): void {
    $mock = mockClient(cwFixture('conversation'));

    applicationApi($mock)->conversations(1)->update(15, ['priority' => 'urgent']);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/conversations/15', body: ['priority' => 'urgent']);
});

it('deletes a conversation', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->conversations(1)->delete(15);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/conversations/15');
});

it('toggles the status', function (): void {
    $payload = ['meta' => [], 'payload' => ['success' => true, 'conversation_id' => 15, 'current_status' => 'snoozed', 'snoozed_until' => null]];
    $mock = mockClient($payload);

    $result = applicationApi($mock)->conversations(1)->toggleStatus(15, ConversationStatus::Snoozed, 1_763_200_000);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/toggle_status', body: ['status' => 'snoozed', 'snoozed_until' => 1_763_200_000]);
    expect($result)->toBe($payload);
});

it('toggles the priority, including clearing it', function (ConversationPriority|string|null $priority, ?string $sent): void {
    $mock = mockClient('');

    applicationApi($mock)->conversations(1)->togglePriority(15, $priority);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/toggle_priority', body: ['priority' => $sent]);
})->with([
    'enum' => [ConversationPriority::Urgent, 'urgent'],
    'string' => ['low', 'low'],
    'clear' => [null, null],
]);

it('toggles the typing status', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->conversations(1)->toggleTypingStatus(15, 'on', true);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/toggle_typing_status', body: ['typing_status' => 'on', 'is_private' => true]);
});

it('updates last seen', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->conversations(1)->updateLastSeen(15);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/update_last_seen');
});

it('sets custom attributes, optionally merging', function (): void {
    $mock = mockClient(['custom_attributes' => ['order' => 'A-2']]);
    expect(applicationApi($mock)->conversations(1)->setCustomAttributes(15, ['order' => 'A-2']))->toBe(['order' => 'A-2']);
    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/custom_attributes', body: ['custom_attributes' => ['order' => 'A-2']]);

    $mock = mockClient(['custom_attributes' => ['order' => 'A-2']]);
    applicationApi($mock)->conversations(1)->setCustomAttributes(15, ['order' => 'A-2'], true);
    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/custom_attributes', body: ['custom_attributes' => ['order' => 'A-2'], 'merge' => true]);
});

it('destroys custom attributes', function (): void {
    $mock = mockClient(['custom_attributes' => []]);

    expect(applicationApi($mock)->conversations(1)->destroyCustomAttributes(15, ['order']))->toBe([]);
    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/destroy_custom_attributes', body: ['custom_attributes' => ['order']]);
});

it('assigns agents, agent bots and teams', function (callable $call, array $body, array|string $response): void {
    $mock = mockClient($response);

    $call(applicationApi($mock)->conversations(1));

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/assignments', body: $body);
})->with([
    'agent' => [fn ($c) => $c->assignAgent(15, 5), ['assignee_id' => 5], cwFixture('agent')],
    'unassign' => [fn ($c) => $c->assignAgent(15, null), ['assignee_id' => null], 'null'],
    'agent bot' => [fn ($c) => $c->assignAgentBot(15, 4), ['assignee_id' => 4, 'assignee_type' => 'AgentBot'], ['id' => 4, 'name' => 'Triage bot']],
    'team' => [fn ($c) => $c->assignTeam(15, 3), ['team_id' => 3], cwFixture('team')],
    'remove team' => [fn ($c) => $c->assignTeam(15, null), ['team_id' => 0], 'null'],
]);

it('lists and sets conversation labels', function (): void {
    $mock = mockClient(['payload' => ['vip']]);
    expect(applicationApi($mock)->conversations(1)->labels(15))->toBe(['vip']);
    assertSent($mock, 'GET', '/api/v1/accounts/1/conversations/15/labels');

    $mock = mockClient(['payload' => ['vip', 'billing']]);
    expect(applicationApi($mock)->conversations(1)->setLabels(15, ['vip', 'billing']))->toBe(['vip', 'billing']);
    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/labels', body: ['labels' => ['vip', 'billing']]);
});

it('lists conversation reporting events', function (): void {
    $mock = mockClient([['id' => 1, 'name' => 'first_response']]);

    expect(applicationApi($mock)->conversations(1)->reportingEvents(15))->toHaveCount(1);
    assertSent($mock, 'GET', '/api/v1/accounts/1/conversations/15/reporting_events');
});
