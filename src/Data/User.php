<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * A user with their account memberships: the profile (`api/v1/models/_user.json.jbuilder`) and the
 * Platform API users (`platform/api/v1/models/_user.json.jbuilder`).
 */
final readonly class User
{
    /**
     * @param  list<array<string, mixed>>  $accounts  Memberships: `{id, name, role, active_at, ...}`.
     * @param  array<array-key, mixed>  $customAttributes
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $email,
        public ?string $displayName,
        public ?string $availableName,
        /** Empty string when none of the user's accounts has API access enabled (profile endpoint). */
        public ?string $accessToken,
        /** The most recently active account. */
        public ?int $accountId,
        public ?string $role,
        public ?bool $confirmed,
        public ?string $avatarUrl,
        public ?string $pubsubToken,
        public array $accounts,
        public array $customAttributes,
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
            email: Cast::nullableString($data, 'email'),
            displayName: Cast::nullableString($data, 'display_name'),
            availableName: Cast::nullableString($data, 'available_name'),
            accessToken: Cast::nullableString($data, 'access_token'),
            accountId: Cast::nullableInt($data, 'account_id'),
            role: Cast::nullableString($data, 'role'),
            confirmed: Cast::nullableBool($data, 'confirmed'),
            avatarUrl: Cast::nullableString($data, 'avatar_url'),
            pubsubToken: Cast::nullableString($data, 'pubsub_token'),
            accounts: Cast::listOfObjects($data, 'accounts'),
            customAttributes: Cast::array($data, 'custom_attributes'),
            raw: $data,
        );
    }
}
