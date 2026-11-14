<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\ApplicationConnector;
use GPIbarra\ChatwootSdk\Data\Inbox;
use GPIbarra\ChatwootSdk\Data\Webhook;

/*
 * Read-only calls against a real Chatwoot. Skipped unless CHATWOOT_TEST_URL, CHATWOOT_TEST_TOKEN and
 * CHATWOOT_TEST_ACCOUNT_ID are set. Nothing here creates, updates or deletes data.
 */

function liveApi(): ApplicationConnector
{
    return new ApplicationConnector((string) getenv('CHATWOOT_TEST_URL'), (string) getenv('CHATWOOT_TEST_TOKEN'));
}

function liveAccountId(): int
{
    return (int) getenv('CHATWOOT_TEST_ACCOUNT_ID');
}

function liveConfigured(): bool
{
    return getenv('CHATWOOT_TEST_URL') && getenv('CHATWOOT_TEST_TOKEN') && getenv('CHATWOOT_TEST_ACCOUNT_ID');
}

const LIVE_SKIP_REASON = 'Set CHATWOOT_TEST_URL, CHATWOOT_TEST_TOKEN and CHATWOOT_TEST_ACCOUNT_ID to run the live suite.';

it('reads the profile', function (): void {
    $profile = liveApi()->profile()->get();

    expect($profile->id)->toBeGreaterThan(0)
        ->and(array_column($profile->accounts, 'id'))->toContain(liveAccountId());
})->skip(! liveConfigured(), LIVE_SKIP_REASON);

it('reads the account', function (): void {
    expect(liveApi()->accounts()->get(liveAccountId())->id)->toBe(liveAccountId());
})->skip(! liveConfigured(), LIVE_SKIP_REASON);

it('lists inboxes', function (): void {
    expect(liveApi()->inboxes(liveAccountId())->list())->toContainOnlyInstancesOf(Inbox::class);
})->skip(! liveConfigured(), LIVE_SKIP_REASON);

it('lists webhooks', function (): void {
    expect(liveApi()->webhooks(liveAccountId())->list())->toContainOnlyInstancesOf(Webhook::class);
})->skip(! liveConfigured(), LIVE_SKIP_REASON);

it('lists agent bots, agents, teams and labels', function (): void {
    $api = liveApi();

    expect($api->agentBots(liveAccountId())->list())->toBeArray()
        ->and($api->agents(liveAccountId())->list())->toBeArray()
        ->and($api->teams(liveAccountId())->list())->toBeArray()
        ->and($api->labels(liveAccountId())->list())->toBeArray();
})->skip(! liveConfigured(), LIVE_SKIP_REASON);
