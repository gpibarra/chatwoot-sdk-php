<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Data\AgentBot;
use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Enums\AgentRole;

it('lists platform accounts', function (): void {
    $mock = mockClient([cwFixture('platform_account')]);

    $accounts = platformApi($mock)->accounts()->list();

    assertSent($mock, 'GET', '/platform/api/v1/accounts');
    expect($accounts[0])->toBeInstanceOf(Account::class)
        ->and($accounts[0]->id)->toBe(21)
        ->and($accounts[0]->name)->toBe('Tenant A')
        ->and($accounts[0]->locale)->toBe('es')
        ->and($accounts[0]->features)->toBe(['inbox_management', 'api_and_webhooks'])
        ->and($accounts[0]->limits)->toBe(['agents' => 5])
        ->and($accounts[0]->customAttributes)->toBe(['tier' => 'pro'])
        ->and($accounts[0]->settings)->toBe([]);
});

it('creates a platform account', function (): void {
    $mock = mockClient(cwFixture('platform_account'));

    $account = platformApi($mock)->accounts()->create('Tenant A', ['locale' => 'es', 'features' => ['help_center' => false]]);

    assertSent($mock, 'POST', '/platform/api/v1/accounts', body: ['locale' => 'es', 'features' => ['help_center' => false], 'name' => 'Tenant A']);
    expect($account->id)->toBe(21);
});

it('gets, updates and deletes a platform account', function (): void {
    $mock = mockClient(cwFixture('platform_account'));
    expect(platformApi($mock)->accounts()->get(21)->name)->toBe('Tenant A');
    assertSent($mock, 'GET', '/platform/api/v1/accounts/21');

    $mock = mockClient(cwFixture('platform_account'));
    platformApi($mock)->accounts()->update(21, ['status' => 'suspended']);
    assertSent($mock, 'PATCH', '/platform/api/v1/accounts/21', body: ['status' => 'suspended']);

    $mock = mockClient('');
    platformApi($mock)->accounts()->delete(21);
    assertSent($mock, 'DELETE', '/platform/api/v1/accounts/21');
});

it('manages account users', function (): void {
    // Platform::Api::V1::AccountUsersController renders the AccountUser records as JSON.
    $mock = mockClient([['account_id' => 21, 'user_id' => 50, 'role' => 'agent']]);
    expect(platformApi($mock)->accountUsers(21)->list())->toBe([['account_id' => 21, 'user_id' => 50, 'role' => 'agent']]);
    assertSent($mock, 'GET', '/platform/api/v1/accounts/21/account_users');

    $mock = mockClient(['account_id' => 21, 'user_id' => 50, 'role' => 'administrator']);
    platformApi($mock)->accountUsers(21)->create(50, AgentRole::Administrator);
    assertSent($mock, 'POST', '/platform/api/v1/accounts/21/account_users', body: ['user_id' => 50, 'role' => 'administrator']);

    $mock = mockClient('');
    platformApi($mock)->accountUsers(21)->delete(50);
    assertSent($mock, 'DELETE', '/platform/api/v1/accounts/21/account_users', body: ['user_id' => 50]);
});

it('lists platform agent bots', function (): void {
    $mock = mockClient([cwFixture('platform_agent_bot')]);

    $bots = platformApi($mock)->agentBots()->list();

    assertSent($mock, 'GET', '/platform/api/v1/agent_bots');
    expect($bots[0])->toBeInstanceOf(AgentBot::class)
        ->and($bots[0]->id)->toBe(30)
        ->and($bots[0]->accountId)->toBeNull()
        ->and($bots[0]->accessToken)->toBe('platform-bot-token')
        ->and($bots[0]->botType)->toBeNull()
        ->and($bots[0]->systemBot)->toBeNull();
});

it('creates, gets, updates and deletes platform agent bots', function (): void {
    $mock = mockClient(cwFixture('platform_agent_bot'));
    platformApi($mock)->agentBots()->create('Global bot', 'https://bot.example.test/global', null, 21);
    assertSent($mock, 'POST', '/platform/api/v1/agent_bots', body: ['name' => 'Global bot', 'outgoing_url' => 'https://bot.example.test/global', 'account_id' => 21]);

    $mock = mockClient(cwFixture('platform_agent_bot'));
    expect(platformApi($mock)->agentBots()->get(30)->name)->toBe('Global bot');
    assertSent($mock, 'GET', '/platform/api/v1/agent_bots/30');

    $mock = mockClient(cwFixture('platform_agent_bot'));
    platformApi($mock)->agentBots()->update(30, ['name' => 'Renamed']);
    assertSent($mock, 'PATCH', '/platform/api/v1/agent_bots/30', body: ['name' => 'Renamed']);

    $mock = mockClient('');
    platformApi($mock)->agentBots()->delete(30);
    assertSent($mock, 'DELETE', '/platform/api/v1/agent_bots/30');
});

it('creates a platform user', function (): void {
    $mock = mockClient(cwFixture('platform_user'));

    $user = platformApi($mock)->users()->create('Bea', 'bea@example.test', 'Secret123!', ['custom_attributes' => ['external_id' => 'u-50']]);

    assertSent($mock, 'POST', '/platform/api/v1/users', body: [
        'custom_attributes' => ['external_id' => 'u-50'],
        'name' => 'Bea',
        'email' => 'bea@example.test',
        'password' => 'Secret123!',
    ]);
    expect($user)->toBeInstanceOf(User::class)
        ->and($user->id)->toBe(50)
        ->and($user->accessToken)->toBe('user-token')
        ->and($user->accountId)->toBe(21)
        ->and($user->role)->toBe('agent')
        ->and($user->customAttributes)->toBe(['external_id' => 'u-50'])
        ->and($user->accounts[0]['name'])->toBe('Tenant A');
});

it('gets, updates and deletes platform users', function (): void {
    $mock = mockClient(cwFixture('platform_user'));
    expect(platformApi($mock)->users()->get(50)->email)->toBe('bea@example.test');
    assertSent($mock, 'GET', '/platform/api/v1/users/50');

    $mock = mockClient(cwFixture('platform_user'));
    platformApi($mock)->users()->update(50, ['display_name' => 'B']);
    assertSent($mock, 'PATCH', '/platform/api/v1/users/50', body: ['display_name' => 'B']);

    $mock = mockClient('');
    platformApi($mock)->users()->delete(50);
    assertSent($mock, 'DELETE', '/platform/api/v1/users/50');
});

it('gets the SSO login URL of a user', function (): void {
    $mock = mockClient(['url' => 'http://chatwoot.test/app/login?email=bea&sso_auth_token=x']);

    expect(platformApi($mock)->users()->loginUrl(50))->toBe('http://chatwoot.test/app/login?email=bea&sso_auth_token=x');
    assertSent($mock, 'GET', '/platform/api/v1/users/50/login');
});

it('gets the access token of a user', function (): void {
    // app/views/platform/api/v1/users/token.json.jbuilder
    $token = ['access_token' => 'user-token', 'expiry' => null, 'user' => ['id' => 50, 'name' => 'Bea', 'display_name' => null, 'email' => 'bea@example.test', 'pubsub_token' => 'p']];
    $mock = mockClient($token);

    expect(platformApi($mock)->users()->token(50))->toBe($token);
    assertSent($mock, 'POST', '/platform/api/v1/users/50/token');
});
