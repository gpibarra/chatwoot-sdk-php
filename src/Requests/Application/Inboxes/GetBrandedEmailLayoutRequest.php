<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get the account's branded email layout.
 *
 * `GET /api/v1/accounts/{accountId}/branded_email_layout`
 */
final class GetBrandedEmailLayoutRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/branded_email_layout";
    }
}
