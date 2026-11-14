<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Agents;

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update an agent's role, availability or auto-offline setting.
 *
 * `PATCH /api/v1/accounts/{accountId}/agents/{agentId}`
 */
final class UpdateAgentRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $agentId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/agents/{$this->agentId}";
    }

    public function createDtoFromResponse(Response $response): Agent
    {
        return Agent::fromArray(ResponseData::object($response));
    }
}
