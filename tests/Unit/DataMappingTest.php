<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Data\Inbox;
use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Exceptions\UnexpectedResponseException;

it('tolerates missing keys', function (): void {
    $inbox = Inbox::fromArray(['id' => '3', 'name' => 'Minimal']);

    expect($inbox->id)->toBe(3)
        ->and($inbox->channelType)->toBe('')
        ->and($inbox->phoneNumber)->toBeNull()
        ->and($inbox->providerConfig)->toBe([]);
});

it('maps the slimmer conversation shape used by search results', function (): void {
    // app/views/api/v1/models/_conversation.json.jbuilder (search endpoint): contact/inbox as nested objects.
    $conversation = Conversation::fromArray([
        'id' => 8,
        'uuid' => 'u',
        'created_at' => 1_763_000_000,
        'contact' => ['id' => 42, 'name' => 'Juan'],
        'inbox' => ['id' => 7, 'name' => 'WhatsApp Sales', 'channel_type' => 'Channel::Whatsapp'],
        'messages' => [['content' => 'hi', 'id' => 1, 'message_type' => 0, 'created_at' => 1_763_000_000]],
        'account_id' => 1,
    ]);

    expect($conversation->contactId)->toBe(42)
        ->and($conversation->inboxId)->toBe(7)
        ->and($conversation->assigneeId)->toBeNull()
        ->and($conversation->messages)->toHaveCount(1);
});

it('keeps attachments of a message', function (): void {
    $message = Message::fromArray(['id' => 1, 'attachments' => [['id' => 2, 'file_type' => 'image', 'data_url' => 'http://x']]]);

    expect($message->attachments[0]['file_type'])->toBe('image')->and($message->sender)->toBeNull();
});

it('fails loudly when a successful response has an unexpected shape', function (): void {
    applicationApi(mockClient(['unexpected' => true]))->inboxes(1)->list();
})->throws(UnexpectedResponseException::class);
