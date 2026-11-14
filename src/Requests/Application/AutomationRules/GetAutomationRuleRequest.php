<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AutomationRules;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get an automation rule.
 *
 * `GET /api/v1/accounts/{accountId}/automation_rules/{automationRuleId}`
 */
final class GetAutomationRuleRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $automationRuleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/automation_rules/{$this->automationRuleId}";
    }
}
