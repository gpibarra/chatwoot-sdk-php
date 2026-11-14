<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Contacts;

use GPIbarra\ChatwootSdk\Data\Contact;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Search contacts by name, identifier, email or phone number.
 *
 * `GET /api/v1/accounts/{accountId}/contacts/search`
 */
final class SearchContactsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        array $query = [],
    ) {
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/contacts/search";
    }

    /**
     * @return Page<Contact>
     */
    public function createDtoFromResponse(Response $response): Page
    {
        $meta = ResponseData::dig(ResponseData::decode($response), ['meta']);

        return new Page(
            items: array_map(Contact::fromArray(...), ResponseData::list($response, 'payload')),
            meta: is_array($meta) ? $meta : [],
            raw: ResponseData::decode($response),
        );
    }
}
