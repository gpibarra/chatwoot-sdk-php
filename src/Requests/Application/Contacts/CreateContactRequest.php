<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Contacts;

use GPIbarra\ChatwootSdk\Data\Contact;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create a contact, optionally attached to an inbox.
 *
 * `POST /api/v1/accounts/{accountId}/contacts`
 */
final class CreateContactRequest extends JsonBodyRequest
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
        return "/api/v1/accounts/{$this->accountId}/contacts";
    }

    public function createDtoFromResponse(Response $response): Contact
    {
        return Contact::fromArray(ResponseData::object($response, 'payload', 'contact'));
    }
}
