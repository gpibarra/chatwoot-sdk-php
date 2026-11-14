<?php

declare(strict_types=1);

// app/views/api/v1/accounts/show.json.jbuilder + app/views/api/v1/models/_account.json.jbuilder (v4.18.0),
// Community edition (no enterprise partial). `custom_attributes` is only rendered when present.
return [
    'settings' => ['auto_resolve_after' => null, 'auto_resolve_message' => null],
    'created_at' => '2026-01-10T12:00:00.000Z',
    'custom_attributes' => [
        'plan_name' => null,
        'subscribed_quantity' => null,
        'subscription_status' => null,
        'subscription_ends_on' => null,
        'subscription_cancels_on' => null,
        'industry' => 'software',
    ],
    'domain' => null,
    'features' => ['inbox_management' => true, 'api_and_webhooks' => true, 'help_center' => false],
    'id' => 1,
    'locale' => 'en',
    'name' => 'Efemaq Lab',
    'support_email' => 'support@example.test',
    'status' => 'active',
    'cache_keys' => ['label' => 1_700_000_000, 'inbox' => 1_700_000_000, 'team' => 1_700_000_000],
    'latest_chatwoot_version' => '4.18.0',
];
