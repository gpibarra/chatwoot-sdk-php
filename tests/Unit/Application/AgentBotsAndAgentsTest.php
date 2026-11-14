<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Enums\AgentRole;

it('lists agent bots (a top-level array)', function (): void {
    $mock = mockClient([cwFixture('agent_bot')]);

    $bots = applicationApi($mock)->agentBots(1)->list();

    assertSent($mock, 'GET', '/api/v1/accounts/1/agent_bots');
    expect($bots)->toHaveCount(1)
        ->and($bots[0])->toBeInstanceOf(AgentBot::class)
        ->and($bots[0]->id)->toBe(4)
        ->and($bots[0]->name)->toBe('Triage bot')
        ->and($bots[0]->description)->toBe('Routes new conversations')
        ->and($bots[0]->outgoingUrl)->toBe('https://bot.example.test/events')
        ->and($bots[0]->botType)->toBe('webhook')
        ->and($bots[0]->accountId)->toBe(1)
        ->and($bots[0]->accessToken)->toBe('bot-token')
        ->and($bots[0]->secret)->toBe('bot-secret')
        ->and($bots[0]->systemBot)->toBeFalse();
});

it('gets an agent bot', function (): void {
    $mock = mockClient(cwFixture('agent_bot'));

    expect(applicationApi($mock)->agentBots(1)->get(4)->id)->toBe(4);
    assertSent($mock, 'GET', '/api/v1/accounts/1/agent_bots/4');
});

it('creates an agent bot', function (): void {
    $mock = mockClient(cwFixture('agent_bot'));

    $bot = applicationApi($mock)->agentBots(1)->create('Triage bot', 'https://bot.example.test/events', 'Routes new conversations', ['bot_config' => ['a' => 1]]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/agent_bots', body: [
        'bot_config' => ['a' => 1],
        'name' => 'Triage bot',
        'outgoing_url' => 'https://bot.example.test/events',
        'description' => 'Routes new conversations',
    ]);
    expect($bot->id)->toBe(4);
});

it('updates an agent bot', function (): void {
    $mock = mockClient(cwFixture('agent_bot'));

    applicationApi($mock)->agentBots(1)->update(4, ['outgoing_url' => 'https://new.test']);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/agent_bots/4', body: ['outgoing_url' => 'https://new.test']);
});

it('deletes an agent bot', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->agentBots(1)->delete(4);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/agent_bots/4');
});

it('lists agents', function (): void {
    $mock = mockClient([cwFixture('agent')]);

    $agents = applicationApi($mock)->agents(1)->list();

    assertSent($mock, 'GET', '/api/v1/accounts/1/agents');
    expect($agents[0])->toBeInstanceOf(Agent::class)
        ->and($agents[0]->id)->toBe(5)
        ->and($agents[0]->name)->toBe('Ana Pérez')
        ->and($agents[0]->email)->toBe('ana@example.test')
        ->and($agents[0]->availableName)->toBe('Ana')
        ->and($agents[0]->role)->toBe('administrator')
        ->and($agents[0]->availabilityStatus)->toBe('online')
        ->and($agents[0]->autoOffline)->toBeTrue()
        ->and($agents[0]->confirmed)->toBeTrue()
        ->and($agents[0]->accountId)->toBe(1);
});

it('creates an agent under the agent root key', function (): void {
    $mock = mockClient(cwFixture('agent'));

    applicationApi($mock)->agents(1)->create('Ana Pérez', 'ana@example.test', AgentRole::Administrator, ['auto_offline' => false]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/agents', body: ['agent' => [
        'auto_offline' => false,
        'name' => 'Ana Pérez',
        'email' => 'ana@example.test',
        'role' => 'administrator',
    ]]);
});

it('updates an agent', function (): void {
    $mock = mockClient(cwFixture('agent'));

    applicationApi($mock)->agents(1)->update(5, ['role' => 'agent']);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/agents/5', body: ['agent' => ['role' => 'agent']]);
});

it('deletes an agent', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->agents(1)->delete(5);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/agents/5');
});
