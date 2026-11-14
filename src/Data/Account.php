<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * An account. Covers both the Application API shape (`api/v1/models/_account.json.jbuilder`, with
 * `settings`, `features` as a map and `cache_keys`) and the Platform API shape
 * (`platform/api/v1/models/_account.json.jbuilder`, with `limits`).
 */
final readonly class Account
{
    /**
     * @param  array<array-key, mixed>  $features  Feature flags (map of name => bool in the Application API).
     * @param  array<array-key, mixed>  $settings
     * @param  array<array-key, mixed>  $customAttributes
     * @param  array<array-key, mixed>  $limits  Platform API only.
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $locale,
        public ?string $domain,
        public ?string $supportEmail,
        /** `active` or `suspended`. */
        public ?string $status,
        public array $features,
        public array $settings,
        public array $customAttributes,
        public array $limits,
        public ?string $latestChatwootVersion,
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
            locale: Cast::nullableString($data, 'locale'),
            domain: Cast::nullableString($data, 'domain'),
            supportEmail: Cast::nullableString($data, 'support_email'),
            status: Cast::nullableString($data, 'status'),
            features: Cast::array($data, 'features'),
            settings: Cast::array($data, 'settings'),
            customAttributes: Cast::array($data, 'custom_attributes'),
            limits: Cast::array($data, 'limits'),
            latestChatwootVersion: Cast::nullableString($data, 'latest_chatwoot_version'),
            raw: $data,
        );
    }
}
