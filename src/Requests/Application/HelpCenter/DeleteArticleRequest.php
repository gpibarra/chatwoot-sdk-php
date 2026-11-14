<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\HelpCenter;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an article.
 *
 * `DELETE /api/v1/accounts/{accountId}/portals/{portalSlug}/articles/{articleId}`
 */
final class DeleteArticleRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly string $portalSlug,
        public readonly int $articleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/portals/{$this->encodedSlug($this->portalSlug)}/articles/{$this->articleId}";
    }

    private function encodedSlug(string $slug): string
    {
        return rawurlencode($slug);
    }
}
