<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * An account webhook, as rendered by `api/v1/accounts/webhooks/_webhook.json.jbuilder`.
 *
 * Chatwoot exposes the scoped inbox as `inbox: {id, name}` (only when set) rather than `inbox_id`;
 * both forms are accepted here.
 */
final readonly class Webhook
{
    /**
     * @param  list<string>  $subscriptions
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $url,
        public ?string $name,
        public array $subscriptions,
        public ?int $inboxId,
        public ?string $inboxName,
        public ?int $accountId,
        /** HMAC secret used to sign deliveries (Chatwoot ≥ 4.x); null when not exposed. */
        public ?string $secret,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $inbox = Cast::nullableObject($data, 'inbox');

        return new self(
            id: Cast::int($data, 'id'),
            url: Cast::string($data, 'url'),
            name: Cast::nullableString($data, 'name'),
            subscriptions: Cast::listOfStrings($data, 'subscriptions'),
            inboxId: $inbox !== null ? Cast::nullableInt($inbox, 'id') : Cast::nullableInt($data, 'inbox_id'),
            inboxName: $inbox !== null ? Cast::nullableString($inbox, 'name') : null,
            accountId: Cast::nullableInt($data, 'account_id'),
            secret: Cast::nullableString($data, 'secret'),
            raw: $data,
        );
    }
}
