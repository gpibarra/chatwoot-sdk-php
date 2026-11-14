<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AutomationRules;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Update an automation rule.
 *
 * `PATCH /api/v1/accounts/{accountId}/automation_rules/{automationRuleId}`
 */
final class UpdateAutomationRuleRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $automationRuleId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/automation_rules/{$this->automationRuleId}";
    }
}
