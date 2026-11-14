<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Contact;
use GPIbarra\ChatwootSdk\Data\Conversation;
use GPIbarra\ChatwootSdk\Data\Page;

it('lists contacts as a page', function (): void {
    // app/views/api/v1/accounts/contacts/index.json.jbuilder: {meta: {count, current_page}, payload: [...]}
    $mock = mockClient(['meta' => ['count' => 31, 'current_page' => '2'], 'payload' => [cwFixture('contact')]]);

    $page = applicationApi($mock)->contacts(1)->list(2, '-last_activity_at', ['include_contact_inboxes' => 'false']);

    assertSent($mock, 'GET', '/api/v1/accounts/1/contacts', ['include_contact_inboxes' => 'false', 'page' => 2, 'sort' => '-last_activity_at']);
    expect($page)->toBeInstanceOf(Page::class)
        ->and($page->meta)->toBe(['count' => 31, 'current_page' => '2'])
        ->and($page->items[0])->toBeInstanceOf(Contact::class)
        ->and($page->items[0]->id)->toBe(42)
        ->and($page->items[0]->name)->toBe('Juan')
        ->and($page->items[0]->email)->toBe('juan@example.test')
        ->and($page->items[0]->phoneNumber)->toBe('+5493410000000')
        ->and($page->items[0]->identifier)->toBe('crm-42')
        ->and($page->items[0]->blocked)->toBeFalse()
        ->and($page->items[0]->availabilityStatus)->toBe('offline')
        ->and($page->items[0]->customAttributes)->toBe(['plan' => 'gold'])
        ->and($page->items[0]->additionalAttributes)->toBe(['city' => 'Rosario'])
        ->and($page->items[0]->createdAt)->toBe(1_763_000_000)
        ->and($page->items[0]->lastActivityAt)->toBe(1_763_100_000)
        ->and($page->items[0]->contactInboxes[0]['source_id'])->toBe('5493410000000');
});

it('creates a contact and unwraps payload.contact', function (): void {
    // app/views/api/v1/accounts/contacts/create.json.jbuilder: {payload: {contact, contact_inbox}}
    $mock = mockClient(['payload' => ['contact' => cwFixture('contact'), 'contact_inbox' => ['inbox' => null, 'source_id' => null]]]);

    $contact = applicationApi($mock)->contacts(1)->create('Juan', 'juan@example.test', '+5493410000000', 'crm-42', 7, ['custom_attributes' => ['plan' => 'gold']]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/contacts', body: [
        'custom_attributes' => ['plan' => 'gold'],
        'name' => 'Juan',
        'email' => 'juan@example.test',
        'phone_number' => '+5493410000000',
        'identifier' => 'crm-42',
        'inbox_id' => 7,
    ]);
    expect($contact->id)->toBe(42);
});

it('gets a contact from the payload envelope', function (): void {
    $mock = mockClient(['payload' => cwFixture('contact')]);

    $contact = applicationApi($mock)->contacts(1)->get(42, true);

    assertSent($mock, 'GET', '/api/v1/accounts/1/contacts/42', ['include_contact_inboxes' => 'true']);
    expect($contact->id)->toBe(42);
});

it('updates a contact with PUT', function (): void {
    $mock = mockClient(['payload' => cwFixture('contact')]);

    applicationApi($mock)->contacts(1)->update(42, ['name' => 'Juan B']);

    assertSent($mock, 'PUT', '/api/v1/accounts/1/contacts/42', body: ['name' => 'Juan B']);
});

it('deletes a contact', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->contacts(1)->delete(42);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/contacts/42');
});

it('lists the conversations of a contact', function (): void {
    $mock = mockClient(['payload' => [cwFixture('conversation')]]);

    $conversations = applicationApi($mock)->contacts(1)->conversations(42);

    assertSent($mock, 'GET', '/api/v1/accounts/1/contacts/42/conversations');
    expect($conversations[0])->toBeInstanceOf(Conversation::class)->and($conversations[0]->id)->toBe(15);
});

it('lists and sets contact labels', function (): void {
    $mock = mockClient(['payload' => ['vip']]);
    expect(applicationApi($mock)->contacts(1)->labels(42))->toBe(['vip']);
    assertSent($mock, 'GET', '/api/v1/accounts/1/contacts/42/labels');

    $mock = mockClient(['payload' => ['vip', 'lead']]);
    expect(applicationApi($mock)->contacts(1)->setLabels(42, ['vip', 'lead']))->toBe(['vip', 'lead']);
    assertSent($mock, 'POST', '/api/v1/accounts/1/contacts/42/labels', body: ['labels' => ['vip', 'lead']]);
});

it('searches contacts', function (): void {
    $mock = mockClient(['meta' => ['count' => 1, 'current_page' => 1], 'payload' => [cwFixture('contact')]]);

    $page = applicationApi($mock)->contacts(1)->search('juan', 1);

    assertSent($mock, 'GET', '/api/v1/accounts/1/contacts/search', ['q' => 'juan', 'page' => 1]);
    expect($page->items)->toHaveCount(1);
});

it('filters contacts', function (): void {
    $mock = mockClient(['meta' => ['count' => 1, 'current_page' => 1], 'payload' => [cwFixture('contact')]]);
    $conditions = [['attribute_key' => 'name', 'filter_operator' => 'contains', 'values' => ['juan'], 'query_operator' => null]];

    $page = applicationApi($mock)->contacts(1)->filter($conditions, 3);

    assertSent($mock, 'POST', '/api/v1/accounts/1/contacts/filter', ['page' => 3], ['payload' => $conditions]);
    expect($page->items[0]->id)->toBe(42);
});

it('creates a contact inbox', function (): void {
    $mock = mockClient(['source_id' => '5493410000000', 'inbox' => ['id' => 7]]);

    $result = applicationApi($mock)->contacts(1)->createContactInbox(42, 7, '5493410000000');

    assertSent($mock, 'POST', '/api/v1/accounts/1/contacts/42/contact_inboxes', body: ['inbox_id' => 7, 'source_id' => '5493410000000']);
    expect($result['source_id'])->toBe('5493410000000');
});

it('lists contactable inboxes', function (): void {
    $mock = mockClient(['payload' => []]);

    expect(applicationApi($mock)->contacts(1)->contactableInboxes(42))->toBe(['payload' => []]);
    assertSent($mock, 'GET', '/api/v1/accounts/1/contacts/42/contactable_inboxes');
});

it('destroys contact custom attributes', function (): void {
    $mock = mockClient(['payload' => cwFixture('contact')]);

    applicationApi($mock)->contacts(1)->destroyCustomAttributes(42, ['plan']);

    assertSent($mock, 'POST', '/api/v1/accounts/1/contacts/42/destroy_custom_attributes', body: ['custom_attributes' => ['plan']]);
});

it('merges contacts', function (): void {
    // app/views/api/v1/accounts/actions/contact_merges/create.json.jbuilder renders the base contact unwrapped.
    $mock = mockClient(cwFixture('contact'));

    $contact = applicationApi($mock)->contacts(1)->merge(42, 43);

    assertSent($mock, 'POST', '/api/v1/accounts/1/actions/contact_merge', body: ['base_contact_id' => 42, 'mergee_contact_id' => 43]);
    expect($contact->id)->toBe(42);
});

it('exports contacts through the v4 route', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->contacts(1)->export(['name', 'email'], ['label' => 'vip']);

    assertSent($mock, 'POST', '/api/v1/accounts/1/contacts/export', body: ['label' => 'vip', 'column_names' => ['name', 'email']]);
});
