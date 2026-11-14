<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * An account label, as rendered by `api/v1/accounts/labels/*.json.jbuilder`.
 */
final readonly class Label
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        /** Hex colour, e.g. `#1f93ff`. */
        public ?string $color,
        public ?bool $showOnSidebar,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Cast::int($data, 'id'),
            title: Cast::string($data, 'title'),
            description: Cast::nullableString($data, 'description'),
            color: Cast::nullableString($data, 'color'),
            showOnSidebar: Cast::nullableBool($data, 'show_on_sidebar'),
            raw: $data,
        );
    }
}
