<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Contacts;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Trigger a contacts CSV export emailed to the user (Chatwoot <= 4.x route).
 *
 * `POST /api/v1/accounts/{accountId}/contacts/export`
 */
final class ExportContactsRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

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
        return "/api/v1/accounts/{$this->accountId}/contacts/export";
    }
}
