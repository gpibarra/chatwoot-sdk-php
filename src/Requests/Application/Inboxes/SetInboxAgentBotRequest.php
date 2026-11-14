<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Connect an agent bot to an inbox, or disconnect it with `agent_bot: null`.
 *
 * `POST /api/v1/accounts/{accountId}/inboxes/{inboxId}/set_agent_bot`
 */
final class SetInboxAgentBotRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $inboxId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inboxes/{$this->inboxId}/set_agent_bot";
    }
}
