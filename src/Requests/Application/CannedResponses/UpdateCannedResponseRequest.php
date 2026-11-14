<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CannedResponses;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Update a canned response.
 *
 * `PATCH /api/v1/accounts/{accountId}/canned_responses/{cannedResponseId}`
 */
final class UpdateCannedResponseRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $cannedResponseId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/canned_responses/{$this->cannedResponseId}";
    }
}
