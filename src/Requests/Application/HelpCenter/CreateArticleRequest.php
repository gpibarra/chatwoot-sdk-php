<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\HelpCenter;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Create an article in a portal.
 *
 * `POST /api/v1/accounts/{accountId}/portals/{portalSlug}/articles`
 */
final class CreateArticleRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly string $portalSlug,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/portals/{$this->encodedSlug($this->portalSlug)}/articles";
    }

    private function encodedSlug(string $slug): string
    {
        return rawurlencode($slug);
    }
}
