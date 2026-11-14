<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Account;
use GPIbarra\ChatwootSdk\Data\User;

it('gets an account', function (): void {
    $mock = mockClient(cwFixture('account'));

    $account = applicationApi($mock)->accounts()->get(1);

    assertSent($mock, 'GET', '/api/v1/accounts/1');
    expect($account)->toBeInstanceOf(Account::class)
        ->and($account->id)->toBe(1)
        ->and($account->name)->toBe('Efemaq Lab')
        ->and($account->locale)->toBe('en')
        ->and($account->domain)->toBeNull()
        ->and($account->supportEmail)->toBe('support@example.test')
        ->and($account->status)->toBe('active')
        ->and($account->features['api_and_webhooks'])->toBeTrue()
        ->and($account->settings)->toHaveKey('auto_resolve_after')
        ->and($account->customAttributes['industry'])->toBe('software')
        ->and($account->latestChatwootVersion)->toBe('4.18.0')
        ->and($account->raw)->toBe(cwFixture('account'));
});

it('updates an account', function (): void {
    $mock = mockClient(cwFixture('account'));

    $account = applicationApi($mock)->accounts()->update(1, ['name' => 'Efemaq Lab', 'auto_resolve_after' => 1440]);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1', body: ['name' => 'Efemaq Lab', 'auto_resolve_after' => 1440]);
    expect($account->id)->toBe(1);
});

it('gets the profile', function (): void {
    $mock = mockClient(cwFixture('user'));

    $profile = applicationApi($mock)->profile()->get();

    assertSent($mock, 'GET', '/api/v1/profile');
    expect($profile)->toBeInstanceOf(User::class)
        ->and($profile->id)->toBe(5)
        ->and($profile->name)->toBe('Ana Pérez')
        ->and($profile->email)->toBe('ana@example.test')
        ->and($profile->displayName)->toBe('Ana')
        ->and($profile->availableName)->toBe('Ana')
        ->and($profile->accessToken)->toBe('user-token')
        ->and($profile->accountId)->toBe(1)
        ->and($profile->role)->toBe('administrator')
        ->and($profile->confirmed)->toBeTrue()
        ->and($profile->pubsubToken)->toBe('pubsub-token')
        ->and($profile->accounts)->toHaveCount(1)
        ->and($profile->accounts[0]['id'])->toBe(1);
});

it('updates the profile under the profile root key', function (): void {
    $mock = mockClient(cwFixture('user'));

    applicationApi($mock)->profile()->update(['display_name' => 'Ana P']);

    assertSent($mock, 'PUT', '/api/v1/profile', body: ['profile' => ['display_name' => 'Ana P']]);
});
