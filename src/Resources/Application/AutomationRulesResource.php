<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\AutomationRules\CreateAutomationRuleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AutomationRules\DeleteAutomationRuleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AutomationRules\GetAutomationRuleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AutomationRules\ListAutomationRulesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\AutomationRules\UpdateAutomationRuleRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

final class AutomationRulesResource extends AccountScopedResource
{
    /**
     * @return array<array-key, mixed> `{payload: [...]}`.
     */
    public function list(?int $page = null): array
    {
        return $this->sendForArray(new ListAutomationRulesRequest($this->accountId, Payload::filter(['page' => $page])));
    }

    /**
     * @return array<array-key, mixed> `{payload: {...}}`.
     */
    public function get(int $automationRuleId): array
    {
        return $this->sendForArray(new GetAutomationRuleRequest($this->accountId, $automationRuleId));
    }

    /**
     * @param  string  $eventName  `conversation_created`, `conversation_updated`, `message_created`, `conversation_opened`, ...
     * @param  list<array<string, mixed>>  $conditions  `{attribute_key, filter_operator, values, query_operator}` entries.
     * @param  list<array<string, mixed>>  $actions  `{action_name, action_params}` entries.
     * @param  array<string, mixed>  $extra  `description`, `active`, ...
     * @return array<array-key, mixed>
     */
    public function create(string $name, string $eventName, array $conditions, array $actions, array $extra = []): array
    {
        return $this->sendForArray(new CreateAutomationRuleRequest($this->accountId, Payload::merge([
            'name' => $name,
            'event_name' => $eventName,
            'conditions' => $conditions,
            'actions' => $actions,
        ], $extra)));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function update(int $automationRuleId, array $attributes): array
    {
        return $this->sendForArray(new UpdateAutomationRuleRequest($this->accountId, $automationRuleId, $attributes));
    }

    public function delete(int $automationRuleId): void
    {
        $this->sendForNothing(new DeleteAutomationRuleRequest($this->accountId, $automationRuleId));
    }
}
