<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\HelpCenter;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a category.
 *
 * `DELETE /api/v1/accounts/{accountId}/portals/{portalSlug}/categories/{categoryId}`
 */
final class DeleteCategoryRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly string $portalSlug,
        public readonly int $categoryId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/portals/{$this->encodedSlug($this->portalSlug)}/categories/{$this->categoryId}";
    }

    private function encodedSlug(string $slug): string
    {
        return rawurlencode($slug);
    }
}
