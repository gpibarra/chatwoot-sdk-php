<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Inboxes;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Remove agents from an inbox.
 *
 * `DELETE /api/v1/accounts/{accountId}/inbox_members`
 */
final class RemoveInboxMembersRequest extends JsonBodyRequest
{
    protected Method $method = Method::DELETE;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/inbox_members";
    }
}
