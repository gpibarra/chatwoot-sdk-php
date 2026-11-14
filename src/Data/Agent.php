<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * An agent (account user), as rendered by `api/v1/models/_agent.json.jbuilder`. Used by the agents,
 * inbox members and team members endpoints and for conversation assignees.
 */
final readonly class Agent
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $email,
        public ?string $availableName,
        /** `agent` or `administrator`. */
        public ?string $role,
        /** `online`, `busy` or `offline`. */
        public ?string $availabilityStatus,
        public ?bool $autoOffline,
        public ?bool $confirmed,
        public ?string $thumbnail,
        public ?int $accountId,
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
            availableName: Cast::nullableString($data, 'available_name'),
            role: Cast::nullableString($data, 'role'),
            availabilityStatus: Cast::nullableString($data, 'availability_status'),
            autoOffline: Cast::nullableBool($data, 'auto_offline'),
            confirmed: Cast::nullableBool($data, 'confirmed'),
            thumbnail: Cast::nullableString($data, 'thumbnail'),
            accountId: Cast::nullableInt($data, 'account_id'),
            raw: $data,
        );
    }
}
