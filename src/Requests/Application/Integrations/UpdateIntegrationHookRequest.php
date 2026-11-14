<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Integrations;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Update an integration hook.
 *
 * `PATCH /api/v1/accounts/{accountId}/integrations/hooks/{hookId}`
 */
final class UpdateIntegrationHookRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $hookId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/integrations/hooks/{$this->hookId}";
    }
}
