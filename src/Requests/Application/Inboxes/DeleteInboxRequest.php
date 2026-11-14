<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete an inbox (performed asynchronously by Chatwoot).
 *
 * `DELETE /api/v1/accounts/{accountId}/inboxes/{inboxId}`
 */
final class DeleteInboxRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $inboxId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inboxes/{$this->inboxId}";
    }
}
