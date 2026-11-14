<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

/**
 * One page of a paginated or meta-carrying listing (contacts, conversations, messages).
 *
 * @template T
 */
final readonly class Page
{
    /**
     * @param  list<T>  $items
     * @param  array<array-key, mixed>  $meta  The listing's `meta` object (counts, current page, conversation context...).
     * @param  array<array-key, mixed>  $raw  The whole decoded response.
     */
    public function __construct(
        public array $items,
        public array $meta,
        public array $raw,
    ) {}
}
