<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * A contact, as rendered by `api/v1/models/_contact.json.jbuilder`.
 */
final readonly class Contact
{
    /**
     * @param  array<array-key, mixed>  $additionalAttributes
     * @param  array<array-key, mixed>  $customAttributes
     * @param  list<array<string, mixed>>  $contactInboxes  `{source_id, inbox: {...}}` entries; only rendered by some endpoints.
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $email,
        public ?string $phoneNumber,
        public ?string $identifier,
        public bool $blocked,
        public ?string $thumbnail,
        public ?string $availabilityStatus,
        public array $additionalAttributes,
        public array $customAttributes,
        /** Unix timestamp. */
        public ?int $createdAt,
        /** Unix timestamp. */
        public ?int $lastActivityAt,
        public array $contactInboxes,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Cast::int($data, 'id'),
            name: Cast::nullableString($data, 'name'),
            email: Cast::nullableString($data, 'email'),
            phoneNumber: Cast::nullableString($data, 'phone_number'),
            identifier: Cast::nullableString($data, 'identifier'),
            blocked: Cast::bool($data, 'blocked'),
            thumbnail: Cast::nullableString($data, 'thumbnail'),
            availabilityStatus: Cast::nullableString($data, 'availability_status'),
            additionalAttributes: Cast::array($data, 'additional_attributes'),
            customAttributes: Cast::array($data, 'custom_attributes'),
            createdAt: Cast::nullableInt($data, 'created_at'),
            lastActivityAt: Cast::nullableInt($data, 'last_activity_at'),
            contactInboxes: Cast::listOfObjects($data, 'contact_inboxes'),
            raw: $data,
        );
    }
}
