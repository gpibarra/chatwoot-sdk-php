<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CannedResponses;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Create a canned response.
 *
 * `POST /api/v1/accounts/{accountId}/canned_responses`
 */
final class CreateCannedResponseRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/canned_responses";
    }
}
