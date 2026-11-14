<?php

declare(strict_types=1);

// app/views/api/v1/models/_contact.json.jbuilder (v4.18.0) with `with_contact_inboxes`;
// contact inboxes follow app/views/api/v1/models/_contact_inbox.json.jbuilder (`source_id`, `inbox`).
return [
    'additional_attributes' => ['city' => 'Rosario'],
    'availability_status' => 'offline',
    'email' => 'juan@example.test',
    'id' => 42,
    'name' => 'Juan',
    'phone_number' => '+5493410000000',
    'blocked' => false,
    'identifier' => 'crm-42',
    'thumbnail' => '',
    'custom_attributes' => ['plan' => 'gold'],
    'last_activity_at' => 1_763_100_000,
    'created_at' => 1_763_000_000,
    'contact_inboxes' => [
        ['source_id' => '5493410000000', 'inbox' => ['id' => 7, 'name' => 'WhatsApp Sales', 'channel_type' => 'Channel::Whatsapp']],
    ],
];
