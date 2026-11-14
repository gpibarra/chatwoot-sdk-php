<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Webhook;
use GPIbarra\ChatwootSdk\Enums\WebhookEvent;

it('lists webhooks from the payload.webhooks envelope', function (): void {
    $unscoped = ['id' => 12, 'name' => null, 'url' => 'https://other.test', 'account_id' => 1, 'subscriptions' => ['contact_created'], 'secret' => 's'];
    $mock = mockClient(['payload' => ['webhooks' => [cwFixture('webhook'), $unscoped]]]);

    $webhooks = applicationApi($mock)->webhooks(1)->list();

    assertSent($mock, 'GET', '/api/v1/accounts/1/webhooks');
    expect($webhooks)->toHaveCount(2)
        ->and($webhooks[0])->toBeInstanceOf(Webhook::class)
        ->and($webhooks[0]->id)->toBe(11)
        ->and($webhooks[0]->url)->toBe('https://bridge.example.test/hooks/chatwoot')
        ->and($webhooks[0]->name)->toBe('whaloc bridge')
        ->and($webhooks[0]->subscriptions)->toBe(['message_created', 'conversation_status_changed'])
        ->and($webhooks[0]->inboxId)->toBe(7)
        ->and($webhooks[0]->inboxName)->toBe('WhatsApp Sales')
        ->and($webhooks[0]->accountId)->toBe(1)
        ->and($webhooks[0]->secret)->toBe('wh-secret')
        ->and($webhooks[0]->raw)->toBe(cwFixture('webhook'))
        ->and($webhooks[1]->name)->toBeNull()
        ->and($webhooks[1]->inboxId)->toBeNull();
});

it('creates a webhook under the webhook root key, accepting enum subscriptions', function (): void {
    $mock = mockClient(['payload' => ['webhook' => cwFixture('webhook')]]);

    $webhook = applicationApi($mock)->webhooks(1)->create(
        'https://bridge.example.test/hooks/chatwoot',
        [WebhookEvent::MessageCreated, 'conversation_status_changed'],
        7,
        'whaloc bridge',
    );

    assertSent($mock, 'POST', '/api/v1/accounts/1/webhooks', body: ['webhook' => [
        'url' => 'https://bridge.example.test/hooks/chatwoot',
        'subscriptions' => ['message_created', 'conversation_status_changed'],
        'inbox_id' => 7,
        'name' => 'whaloc bridge',
    ]]);
    expect($webhook->id)->toBe(11)->and($webhook->inboxId)->toBe(7);
});

it('omits inbox_id and name when not given', function (): void {
    $mock = mockClient(['payload' => ['webhook' => cwFixture('webhook')]]);

    applicationApi($mock)->webhooks(1)->create('https://x.test', ['message_created']);

    assertSent($mock, 'POST', '/api/v1/accounts/1/webhooks', body: ['webhook' => [
        'url' => 'https://x.test',
        'subscriptions' => ['message_created'],
    ]]);
});

it('updates a webhook', function (): void {
    $mock = mockClient(['payload' => ['webhook' => cwFixture('webhook')]]);

    $webhook = applicationApi($mock)->webhooks(1)->update(11, ['subscriptions' => [WebhookEvent::ContactUpdated], 'name' => 'x']);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/webhooks/11', body: ['webhook' => [
        'subscriptions' => ['contact_updated'],
        'name' => 'x',
    ]]);
    expect($webhook)->toBeInstanceOf(Webhook::class);
});

it('deletes a webhook', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->webhooks(1)->delete(11);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/webhooks/11');
});

it('accepts an inbox_id key when a payload carries one', function (): void {
    expect(Webhook::fromArray(['id' => 1, 'url' => 'u', 'inbox_id' => 3])->inboxId)->toBe(3);
});

it('exposes the subscription names allowed by Chatwoot', function (): void {
    expect(WebhookEvent::values())->toBe([
        'conversation_status_changed', 'conversation_updated', 'conversation_created', 'contact_created', 'contact_updated',
        'message_created', 'message_updated', 'webwidget_triggered', 'inbox_created', 'inbox_updated',
        'conversation_typing_on', 'conversation_typing_off',
    ]);
});
