<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Contacts;

use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use Saloon\Enums\Method;

/**
 * Set the labels of a contact (replaces the current list).
 *
 * `POST /api/v1/accounts/{accountId}/contacts/{contactId}/labels`
 */
final class AddContactLabelsRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $contactId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/contacts/{$this->contactId}/labels";
    }
}
