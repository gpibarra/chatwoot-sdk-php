<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Data\Inbox;

it('lists inboxes from the payload envelope', function (): void {
    $mock = mockClient(['payload' => [cwFixture('inbox_whatsapp'), cwFixture('inbox_api')]]);

    $inboxes = applicationApi($mock)->inboxes(1)->list();

    assertSent($mock, 'GET', '/api/v1/accounts/1/inboxes');
    expect($inboxes)->toHaveCount(2)
        ->and($inboxes[0])->toBeInstanceOf(Inbox::class)
        ->and($inboxes[0]->id)->toBe(7)
        ->and($inboxes[0]->name)->toBe('WhatsApp Sales')
        ->and($inboxes[0]->channelType)->toBe('Channel::Whatsapp')
        ->and($inboxes[0]->channelId)->toBe(3)
        ->and($inboxes[0]->phoneNumber)->toBe('+5491100000000')
        ->and($inboxes[0]->provider)->toBe('whatsapp_cloud')
        ->and($inboxes[0]->providerConfig['phone_number_id'])->toBe('100000000000001')
        ->and($inboxes[0]->raw)->toBe(cwFixture('inbox_whatsapp'))
        ->and($inboxes[1]->phoneNumber)->toBeNull()
        ->and($inboxes[1]->provider)->toBeNull()
        ->and($inboxes[1]->providerConfig)->toBe([])
        ->and($inboxes[1]->webhookUrl)->toBe('https://example.test/chatwoot')
        ->and($inboxes[1]->inboxIdentifier)->toBe('QmFja2VuZA');
});

it('gets an inbox', function (): void {
    $mock = mockClient(cwFixture('inbox_whatsapp'));

    $inbox = applicationApi($mock)->inboxes(1)->get(7);

    assertSent($mock, 'GET', '/api/v1/accounts/1/inboxes/7');
    expect($inbox->id)->toBe(7)->and($inbox->provider)->toBe('whatsapp_cloud');
});

it('creates a WhatsApp Cloud inbox with the channel and extra attributes', function (): void {
    $mock = mockClient(cwFixture('inbox_whatsapp'));
    $channel = [
        'type' => 'whatsapp',
        'phone_number' => '+5491100000000',
        'provider' => 'whatsapp_cloud',
        'provider_config' => [
            'api_key' => 'EAAG-test',
            'phone_number_id' => '100000000000001',
            'business_account_id' => '200000000000002',
            'app_secret' => 'app-secret',
            'webhook_verify_token' => 'verify-me',
            'source' => 'manual_setup_v2',
        ],
    ];

    $inbox = applicationApi($mock)->inboxes(1)->create('WhatsApp Sales', $channel, ['enable_auto_assignment' => false]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/inboxes', body: [
        'enable_auto_assignment' => false,
        'name' => 'WhatsApp Sales',
        'channel' => $channel,
    ]);
    expect($inbox)->toBeInstanceOf(Inbox::class)->and($inbox->id)->toBe(7);
});

it('updates an inbox', function (): void {
    $mock = mockClient(cwFixture('inbox_whatsapp'));

    $inbox = applicationApi($mock)->inboxes(1)->update(7, ['name' => 'Renamed', 'channel' => ['provider_config' => ['api_key' => 'new']]]);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/inboxes/7', body: [
        'name' => 'Renamed',
        'channel' => ['provider_config' => ['api_key' => 'new']],
    ]);
    expect($inbox->id)->toBe(7);
});

it('deletes an inbox', function (): void {
    // Api::V1::Accounts::InboxesController#destroy renders {message: ...} and deletes in a job.
    $mock = mockClient(['message' => 'Your inbox deletion request will be processed in some time.']);

    applicationApi($mock)->inboxes(1)->delete(7);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/inboxes/7');
});

it('sets the agent bot of an inbox', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->inboxes(1)->setAgentBot(7, 4);

    assertSent($mock, 'POST', '/api/v1/accounts/1/inboxes/7/set_agent_bot', body: ['agent_bot' => 4]);
});

it('disconnects the agent bot of an inbox by sending a null agent_bot', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->inboxes(1)->setAgentBot(7, null);

    assertSent($mock, 'POST', '/api/v1/accounts/1/inboxes/7/set_agent_bot', body: ['agent_bot' => null]);
});

it('gets the agent bot of an inbox', function (): void {
    // app/views/api/v1/accounts/inboxes/agent_bot.json.jbuilder wraps the bot in `agent_bot`.
    $mock = mockClient(['agent_bot' => cwFixture('agent_bot')]);

    $bot = applicationApi($mock)->inboxes(1)->agentBot(7);

    assertSent($mock, 'GET', '/api/v1/accounts/1/inboxes/7/agent_bot');
    expect($bot)->toBeInstanceOf(AgentBot::class)->and($bot?->id)->toBe(4);
});

it('returns null when the inbox has no agent bot', function (array|string $body): void {
    $mock = mockClient($body);

    expect(applicationApi($mock)->inboxes(1)->agentBot(7))->toBeNull();
})->with([
    'empty object' => [['agent_bot' => []]],
    'null' => [['agent_bot' => null]],
]);

it('lists the WhatsApp message templates of an inbox', function (): void {
    $mock = mockClient(['payload' => [], 'meta' => []]);

    $templates = applicationApi($mock)->inboxes(1)->messageTemplates(7, 'welcome');

    assertSent($mock, 'GET', '/api/v1/accounts/1/inboxes/7/message_templates', ['name' => 'welcome']);
    expect($templates)->toBe(['payload' => [], 'meta' => []]);
});

it('lists inbox members', function (): void {
    $mock = mockClient(['payload' => [cwFixture('agent')]]);

    $members = applicationApi($mock)->inboxes(1)->members(7);

    assertSent($mock, 'GET', '/api/v1/accounts/1/inbox_members/7');
    expect($members)->toHaveCount(1)->and($members[0])->toBeInstanceOf(Agent::class)->and($members[0]->id)->toBe(5);
});

it('adds inbox members', function (): void {
    $mock = mockClient(['payload' => [cwFixture('agent')]]);

    $members = applicationApi($mock)->inboxes(1)->addMembers(7, [5, 6]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/inbox_members', body: ['inbox_id' => 7, 'user_ids' => [5, 6]]);
    expect($members[0]->email)->toBe('ana@example.test');
});

it('replaces inbox members', function (): void {
    $mock = mockClient(['payload' => [cwFixture('agent')]]);

    applicationApi($mock)->inboxes(1)->updateMembers(7, [5]);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/inbox_members', body: ['inbox_id' => 7, 'user_ids' => [5]]);
});

it('removes inbox members', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->inboxes(1)->removeMembers(7, [6]);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/inbox_members', body: ['inbox_id' => 7, 'user_ids' => [6]]);
});

it('gets and updates the branded email layout', function (): void {
    $mock = mockClient(['branded_email_layout' => '<html>{{ content }}</html>']);
    expect(applicationApi($mock)->inboxes(1)->brandedEmailLayout())->toBe('<html>{{ content }}</html>');
    assertSent($mock, 'GET', '/api/v1/accounts/1/branded_email_layout');

    $mock = mockClient(['branded_email_layout' => null]);
    expect(applicationApi($mock)->inboxes(1)->updateBrandedEmailLayout(null))->toBeNull();
    assertSent($mock, 'PATCH', '/api/v1/accounts/1/branded_email_layout', body: ['branded_email_layout' => null]);
});
