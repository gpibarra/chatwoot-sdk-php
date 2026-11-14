<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Integrations;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an integration hook.
 *
 * `DELETE /api/v1/accounts/{accountId}/integrations/hooks/{hookId}`
 */
final class DeleteIntegrationHookRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $hookId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/integrations/hooks/{$this->hookId}";
    }
}
