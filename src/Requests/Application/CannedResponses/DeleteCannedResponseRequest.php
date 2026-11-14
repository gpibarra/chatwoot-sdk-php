<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CannedResponses;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a canned response.
 *
 * `DELETE /api/v1/accounts/{accountId}/canned_responses/{cannedResponseId}`
 */
final class DeleteCannedResponseRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $cannedResponseId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/canned_responses/{$this->cannedResponseId}";
    }
}
