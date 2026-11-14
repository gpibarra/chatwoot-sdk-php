<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * A message, as rendered by `api/v1/models/_message.json.jbuilder`.
 */
final readonly class Message
{
    public const TYPE_INCOMING = 0;

    public const TYPE_OUTGOING = 1;

    public const TYPE_ACTIVITY = 2;

    public const TYPE_TEMPLATE = 3;

    /**
     * @param  array<array-key, mixed>  $contentAttributes
     * @param  array<string, mixed>|null  $sender  Push-event data of the contact, agent or bot that sent it.
     * @param  list<array<string, mixed>>  $attachments
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $content,
        public ?int $conversationId,
        public ?int $inboxId,
        /** 0 incoming, 1 outgoing, 2 activity, 3 template (see the TYPE_* constants). */
        public ?int $messageType,
        public ?string $contentType,
        /** `sent`, `delivered`, `read` or `failed`. */
        public ?string $status,
        public bool $private,
        public ?string $sourceId,
        public array $contentAttributes,
        /** Unix timestamp. */
        public ?int $createdAt,
        public ?array $sender,
        public array $attachments,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Cast::int($data, 'id'),
            content: Cast::nullableString($data, 'content'),
            conversationId: Cast::nullableInt($data, 'conversation_id'),
            inboxId: Cast::nullableInt($data, 'inbox_id'),
            messageType: Cast::nullableInt($data, 'message_type'),
            contentType: Cast::nullableString($data, 'content_type'),
            status: Cast::nullableString($data, 'status'),
            private: Cast::bool($data, 'private'),
            sourceId: Cast::nullableString($data, 'source_id'),
            contentAttributes: Cast::array($data, 'content_attributes'),
            createdAt: Cast::nullableInt($data, 'created_at'),
            sender: Cast::nullableObject($data, 'sender'),
            attachments: Cast::listOfObjects($data, 'attachments'),
            raw: $data,
        );
    }
}
