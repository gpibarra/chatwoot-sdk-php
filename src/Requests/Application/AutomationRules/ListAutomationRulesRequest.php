<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\AutomationRules;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List automation rules.
 *
 * `GET /api/v1/accounts/{accountId}/automation_rules`
 */
final class ListAutomationRulesRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        array $query = [],
    ) {
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/automation_rules";
    }
}
