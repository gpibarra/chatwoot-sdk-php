<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Contact;
use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\AddContactLabelsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\CreateContactInboxRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\CreateContactRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\DeleteContactRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\DestroyContactCustomAttributesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\ExportContactsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\FilterContactsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\GetContactRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\ListContactableInboxesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\ListContactConversationsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\ListContactLabelsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\ListContactsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\MergeContactsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\SearchContactsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Contacts\UpdateContactRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Cast;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Contacts, their labels, contact inboxes, search, filter and merge.
 */
final class ContactsResource extends AccountScopedResource
{
    /**
     * @param  string|null  $sort  `name`, `email`, `phone_number`, `last_activity_at`, `created_at`... prefix `-` for descending.
     * @param  array<string, mixed>  $query  e.g. `['include_contact_inboxes' => 'false']`.
     * @return Page<Contact> `meta` holds `count` and `current_page`.
     */
    public function list(?int $page = null, ?string $sort = null, array $query = []): Page
    {
        $request = new ListContactsRequest($this->accountId, Payload::merge(['page' => $page, 'sort' => $sort], $query));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  int|null  $inboxId  When given, a contact inbox is created as well.
     * @param  array<string, mixed>  $extra  `source_id`, `blocked`, `avatar_url`, `additional_attributes`, `custom_attributes`.
     */
    public function create(
        ?string $name = null,
        ?string $email = null,
        ?string $phoneNumber = null,
        ?string $identifier = null,
        ?int $inboxId = null,
        array $extra = [],
    ): Contact {
        $request = new CreateContactRequest($this->accountId, Payload::merge([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'identifier' => $identifier,
            'inbox_id' => $inboxId,
        ], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $contactId, ?bool $includeContactInboxes = null): Contact
    {
        $query = $includeContactInboxes === null ? [] : ['include_contact_inboxes' => $includeContactInboxes ? 'true' : 'false'];
        $request = new GetContactRequest($this->accountId, $contactId, $query);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Custom and additional attributes are merged into the existing ones by Chatwoot.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function update(int $contactId, array $attributes): Contact
    {
        $request = new UpdateContactRequest($this->accountId, $contactId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $contactId): void
    {
        $this->sendForNothing(new DeleteContactRequest($this->accountId, $contactId));
    }

    /**
     * @return list<Conversation>
     */
    public function conversations(int $contactId): array
    {
        $request = new ListContactConversationsRequest($this->accountId, $contactId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @return list<string>
     */
    public function labels(int $contactId): array
    {
        return Cast::listOfStrings($this->sendForArray(new ListContactLabelsRequest($this->accountId, $contactId)), 'payload');
    }

    /**
     * Replaces the contact's labels with the given list.
     *
     * @param  list<string>  $labels
     * @return list<string> The labels after the change.
     */
    public function setLabels(int $contactId, array $labels): array
    {
        $response = $this->sendForArray(new AddContactLabelsRequest($this->accountId, $contactId, ['labels' => $labels]));

        return Cast::listOfStrings($response, 'payload');
    }

    /**
     * @param  string  $q  Matched against name, identifier, email and phone number.
     * @return Page<Contact>
     */
    public function search(string $q, ?int $page = null, ?string $sort = null): Page
    {
        $request = new SearchContactsRequest($this->accountId, Payload::filter(['q' => $q, 'page' => $page, 'sort' => $sort]));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<array<string, mixed>>  $payload  Filter conditions: `{attribute_key, filter_operator, values, query_operator}`.
     * @return Page<Contact>
     */
    public function filter(array $payload, ?int $page = null): Page
    {
        $request = new FilterContactsRequest($this->accountId, ['payload' => $payload], Payload::filter(['page' => $page]));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Links the contact to an inbox.
     *
     * @param  string|null  $sourceId  Channel-specific contact ID (phone number for WhatsApp, etc.); generated when null.
     * @return array<array-key, mixed> The contact inbox: `{source_id, inbox: {...}}`.
     */
    public function createContactInbox(int $contactId, int $inboxId, ?string $sourceId = null): array
    {
        return $this->sendForArray(new CreateContactInboxRequest($this->accountId, $contactId, Payload::filter([
            'inbox_id' => $inboxId,
            'source_id' => $sourceId,
        ])));
    }

    /**
     * @return array<array-key, mixed> `{payload: [{source_id, inbox}, ...]}`.
     */
    public function contactableInboxes(int $contactId): array
    {
        return $this->sendForArray(new ListContactableInboxesRequest($this->accountId, $contactId));
    }

    /**
     * @param  list<string>  $keys  Custom attribute keys to remove.
     * @return array<array-key, mixed>
     */
    public function destroyCustomAttributes(int $contactId, array $keys): array
    {
        return $this->sendForArray(new DestroyContactCustomAttributesRequest($this->accountId, $contactId, ['custom_attributes' => $keys]));
    }

    /**
     * Merges `$mergeeContactId` into `$baseContactId` (the mergee is deleted).
     */
    public function merge(int $baseContactId, int $mergeeContactId): Contact
    {
        $request = new MergeContactsRequest($this->accountId, [
            'base_contact_id' => $baseContactId,
            'mergee_contact_id' => $mergeeContactId,
        ]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Triggers a CSV export that Chatwoot emails to the user (`POST /contacts/export`, the route in v4.18.0).
     *
     * @param  list<string>  $columnNames
     * @param  array<string, mixed>  $extra  `payload` (filter conditions), `label`.
     * @return array<array-key, mixed>
     */
    public function export(array $columnNames = [], array $extra = []): array
    {
        return $this->sendForArray(new ExportContactsRequest($this->accountId, Payload::merge([
            'column_names' => $columnNames === [] ? null : $columnNames,
        ], $extra)));
    }
}
