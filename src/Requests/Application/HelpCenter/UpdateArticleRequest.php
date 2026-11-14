<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\HelpCenter;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Update an article.
 *
 * `PATCH /api/v1/accounts/{accountId}/portals/{portalSlug}/articles/{articleId}`
 */
final class UpdateArticleRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly string $portalSlug,
        public readonly int $articleId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/portals/{$this->encodedSlug($this->portalSlug)}/articles/{$this->articleId}";
    }

    private function encodedSlug(string $slug): string
    {
        return rawurlencode($slug);
    }
}
