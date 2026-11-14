<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * A conversation, as rendered by `api/v1/conversations/partials/_conversation.json.jbuilder`.
 *
 * `id` is the account-scoped display ID (the number shown in the dashboard and used in every
 * conversation URL), not the database primary key.
 */
final readonly class Conversation
{
    /**
     * @param  list<string>  $labels
     * @param  array<array-key, mixed>  $customAttributes
     * @param  array<array-key, mixed>  $additionalAttributes
     * @param  list<array<string, mixed>>  $messages  The latest message only, when present.
     * @param  array<array-key, mixed>  $meta  `{sender, assignee, assignee_type, team, channel, hmac_verified}`.
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $uuid,
        public ?int $accountId,
        public ?int $inboxId,
        /** `open`, `resolved`, `pending` or `snoozed`. */
        public ?string $status,
        /** `low`, `medium`, `high`, `urgent` or null. */
        public ?string $priority,
        public ?int $contactId,
        public ?int $assigneeId,
        public ?int $teamId,
        public array $labels,
        public array $customAttributes,
        public array $additionalAttributes,
        public ?int $unreadCount,
        /** Unix timestamp. */
        public ?int $createdAt,
        /** Unix timestamp. */
        public ?int $lastActivityAt,
        public array $messages,
        public array $meta,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $meta = Cast::array($data, 'meta');
        $sender = Cast::nullableObject($meta, 'sender') ?? Cast::nullableObject($data, 'contact');
        $assignee = Cast::nullableObject($meta, 'assignee');
        $team = Cast::nullableObject($meta, 'team');

        return new self(
            id: Cast::int($data, 'id'),
            uuid: Cast::nullableString($data, 'uuid'),
            accountId: Cast::nullableInt($data, 'account_id'),
            inboxId: Cast::nullableInt($data, 'inbox_id') ?? self::nestedId($data, 'inbox'),
            status: Cast::nullableString($data, 'status'),
            priority: Cast::nullableString($data, 'priority'),
            contactId: $sender !== null ? Cast::nullableInt($sender, 'id') : Cast::nullableInt($data, 'contact_id'),
            assigneeId: $assignee !== null ? Cast::nullableInt($assignee, 'id') : Cast::nullableInt($data, 'assignee_id'),
            teamId: $team !== null ? Cast::nullableInt($team, 'id') : Cast::nullableInt($data, 'team_id'),
            labels: Cast::listOfStrings($data, 'labels'),
            customAttributes: Cast::array($data, 'custom_attributes'),
            additionalAttributes: Cast::array($data, 'additional_attributes'),
            unreadCount: Cast::nullableInt($data, 'unread_count'),
            createdAt: Cast::nullableInt($data, 'created_at'),
            lastActivityAt: Cast::nullableInt($data, 'last_activity_at'),
            messages: Cast::listOfObjects($data, 'messages'),
            meta: $meta,
            raw: $data,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function nestedId(array $data, string $key): ?int
    {
        $nested = Cast::nullableObject($data, $key);

        return $nested !== null ? Cast::nullableInt($nested, 'id') : null;
    }
}
