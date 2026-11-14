<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\Integrations\CreateIntegrationHookRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Integrations\DeleteIntegrationHookRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Integrations\ListIntegrationAppsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Integrations\UpdateIntegrationHookRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Integration apps and hooks. Hook bodies are sent under the `hook` root key the controller requires.
 */
final class IntegrationsResource extends AccountScopedResource
{
    /**
     * @return array<array-key, mixed> `{payload: [{id, name, enabled, hooks: [...]}, ...]}`.
     */
    public function apps(): array
    {
        return $this->sendForArray(new ListIntegrationAppsRequest($this->accountId));
    }

    /**
     * @param  string  $appId  The app identifier, e.g. `dialogflow`, `slack`, `openai` (a string, not a number).
     * @param  array<string, mixed>  $settings
     * @param  array<string, mixed>  $extra  e.g. `status`.
     * @return array<array-key, mixed>
     */
    public function createHook(string $appId, array $settings = [], ?int $inboxId = null, array $extra = []): array
    {
        return $this->sendForArray(new CreateIntegrationHookRequest($this->accountId, [
            'hook' => Payload::merge(['app_id' => $appId, 'inbox_id' => $inboxId, 'settings' => $settings], $extra),
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes  `status`, `settings` (other keys are ignored by Chatwoot).
     * @return array<array-key, mixed>
     */
    public function updateHook(int $hookId, array $attributes): array
    {
        return $this->sendForArray(new UpdateIntegrationHookRequest($this->accountId, $hookId, ['hook' => $attributes]));
    }

    public function deleteHook(int $hookId): void
    {
        $this->sendForNothing(new DeleteIntegrationHookRequest($this->accountId, $hookId));
    }
}
