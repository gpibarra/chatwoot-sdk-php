<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * An inbox, as rendered by `api/v1/models/_inbox.json.jbuilder`.
 *
 * Channel-specific keys are only present for the matching channel type, and several of them
 * (`provider_config`, `hmac_token`, `secret`, IMAP/SMTP credentials) are only exposed to account
 * administrators; everything else is available in {@see self::$raw}.
 */
final readonly class Inbox
{
    /**
     * @param  array<array-key, mixed>  $providerConfig  WhatsApp provider settings; empty for non-admin tokens or other channels.
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $name,
        /** Rails class name of the channel, e.g. `Channel::Whatsapp`, `Channel::Api`, `Channel::WebWidget`. */
        public string $channelType,
        public ?int $channelId,
        public ?string $phoneNumber,
        /** e.g. `whatsapp_cloud` or `default` (360dialog) for WhatsApp inboxes. */
        public ?string $provider,
        public array $providerConfig,
        public ?string $avatarUrl,
        /** API channel only: where Chatwoot posts outgoing messages. */
        public ?string $webhookUrl,
        /** API channel only: identifier used by the public client API. */
        public ?string $inboxIdentifier,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Cast::int($data, 'id'),
            name: Cast::string($data, 'name'),
            channelType: Cast::string($data, 'channel_type'),
            channelId: Cast::nullableInt($data, 'channel_id'),
            phoneNumber: Cast::nullableString($data, 'phone_number'),
            provider: Cast::nullableString($data, 'provider'),
            providerConfig: Cast::array($data, 'provider_config'),
            avatarUrl: Cast::nullableString($data, 'avatar_url'),
            webhookUrl: Cast::nullableString($data, 'webhook_url'),
            inboxIdentifier: Cast::nullableString($data, 'inbox_identifier'),
            raw: $data,
        );
    }
}
