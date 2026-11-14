<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AutomationRules;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an automation rule.
 *
 * `DELETE /api/v1/accounts/{accountId}/automation_rules/{automationRuleId}`
 */
final class DeleteAutomationRuleRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $automationRuleId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/automation_rules/{$this->automationRuleId}";
    }
}
