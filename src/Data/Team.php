<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * A team, as rendered by `api/v1/models/_team.json.jbuilder`.
 */
final readonly class Team
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public bool $allowAutoAssign,
        public ?int $accountId,
        /** Whether the token's user belongs to the team. */
        public ?bool $isMember,
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
            description: Cast::nullableString($data, 'description'),
            allowAutoAssign: Cast::bool($data, 'allow_auto_assign'),
            accountId: Cast::nullableInt($data, 'account_id'),
            isMember: Cast::nullableBool($data, 'is_member'),
            raw: $data,
        );
    }
}
