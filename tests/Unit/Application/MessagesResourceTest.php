<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Message;
use Saloon\Data\MultipartValue;
use Saloon\Repositories\Body\MultipartBodyRepository;

it('lists messages as a page with the conversation meta', function (): void {
    // app/views/api/v1/accounts/conversations/messages/index.json.jbuilder
    $meta = ['labels' => ['vip'], 'additional_attributes' => [], 'contact' => ['id' => 42], 'agent_last_seen_at' => null, 'assignee_last_seen_at' => null];
    $mock = mockClient(['meta' => $meta, 'payload' => [cwFixture('message')]]);

    $page = applicationApi($mock)->messages(1, 15)->list(before: 2000);

    assertSent($mock, 'GET', '/api/v1/accounts/1/conversations/15/messages', ['before' => 2000]);
    expect($page->meta)->toBe($meta)
        ->and($page->items[0])->toBeInstanceOf(Message::class)
        ->and($page->items[0]->id)->toBe(1001)
        ->and($page->items[0]->content)->toBe('Hello there')
        ->and($page->items[0]->conversationId)->toBe(15)
        ->and($page->items[0]->inboxId)->toBe(7)
        ->and($page->items[0]->messageType)->toBe(Message::TYPE_OUTGOING)
        ->and($page->items[0]->contentType)->toBe('text')
        ->and($page->items[0]->status)->toBe('sent')
        ->and($page->items[0]->private)->toBeFalse()
        ->and($page->items[0]->sourceId)->toBeNull()
        ->and($page->items[0]->createdAt)->toBe(1_763_100_500)
        ->and($page->items[0]->sender['id'] ?? null)->toBe(5)
        ->and($page->items[0]->attachments)->toBe([]);
});

it('creates a message', function (): void {
    $mock = mockClient(cwFixture('message'));

    $message = applicationApi($mock)->messages(1, 15)->create('Hello there', extra: ['content_attributes' => ['in_reply_to' => 1000]]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/conversations/15/messages', body: [
        'content_attributes' => ['in_reply_to' => 1000],
        'content' => 'Hello there',
        'message_type' => 'outgoing',
        'private' => false,
    ]);
    expect($message->id)->toBe(1001);
});

it('creates a message with attachments as multipart', function (): void {
    $mock = mockClient(cwFixture('message'));
    $file = tempnam(sys_get_temp_dir(), 'cw');
    file_put_contents($file, 'png-bytes');

    try {
        $message = applicationApi($mock)->messages(1, 15)->createWithAttachments([$file], 'See attached', private: true);
    } finally {
        @unlink($file);
    }

    $pending = lastRequest($mock);
    $body = $pending->body();
    expect($pending->getMethod()->value)->toBe('POST')
        ->and($pending->getUrl())->toBe(TEST_BASE_URL.'/api/v1/accounts/1/conversations/15/messages')
        ->and($body)->toBeInstanceOf(MultipartBodyRepository::class)
        ->and($pending->headers()->get('Content-Type'))->toStartWith('multipart/form-data');
    assert($body instanceof MultipartBodyRepository);
    $parts = array_map(fn ($part) => [$part->name, $part->filename], $body->all());
    expect($parts)->toBe([
        ['content', null],
        ['message_type', null],
        ['private', null],
        ['attachments[]', basename($file)],
    ]);
    $private = $body->get('private');
    expect($private)->toBeInstanceOf(MultipartValue::class);
    assert($private instanceof MultipartValue);
    expect($private->value)->toBe('true')->and($message->id)->toBe(1001);
});

it('rejects unreadable attachment paths', function (): void {
    applicationApi(mockClient([]))->messages(1, 15)->createWithAttachments(['/nonexistent/file.png']);
})->throws(InvalidArgumentException::class);

it('updates a message status', function (): void {
    $mock = mockClient(cwFixture('message'));

    applicationApi($mock)->messages(1, 15)->updateStatus(1001, 'failed', 'Undeliverable');

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/conversations/15/messages/1001', body: ['status' => 'failed', 'external_error' => 'Undeliverable']);
});

it('deletes a message', function (): void {
    $mock = mockClient(cwFixture('message'));

    applicationApi($mock)->messages(1, 15)->delete(1001);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/conversations/15/messages/1001');
});
