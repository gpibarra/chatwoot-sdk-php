<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Integrations;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List the integration apps and their hooks.
 *
 * `GET /api/v1/accounts/{accountId}/integrations/apps`
 */
final class ListIntegrationAppsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/integrations/apps";
    }
}
