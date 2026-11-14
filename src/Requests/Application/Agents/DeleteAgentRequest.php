<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Agents;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Remove an agent from the account.
 *
 * `DELETE /api/v1/accounts/{accountId}/agents/{agentId}`
 */
final class DeleteAgentRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $agentId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agents/{$this->agentId}";
    }
}
