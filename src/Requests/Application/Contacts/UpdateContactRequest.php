<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Contacts;

use GPIbarra\ChatwootSdk\Data\Contact;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update a contact (custom and additional attributes are merged).
 *
 * `PUT /api/v1/accounts/{accountId}/contacts/{contactId}`
 */
final class UpdateContactRequest extends JsonBodyRequest
{
    protected Method $method = Method::PUT;

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
        return "/api/v1/accounts/{$this->accountId}/contacts/{$this->contactId}";
    }

    public function createDtoFromResponse(Response $response): Contact
    {
        return Contact::fromArray(ResponseData::object($response, 'payload'));
    }
}
