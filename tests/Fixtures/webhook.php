<?php

declare(strict_types=1);

// app/views/api/v1/accounts/webhooks/_webhook.json.jbuilder (v4.18.0): the inbox is rendered as
// `inbox: {id, name}` only when the webhook is scoped to one; there is no `inbox_id` key.
return [
    'id' => 11,
    'name' => 'whaloc bridge',
    'url' => 'https://bridge.example.test/hooks/chatwoot',
    'account_id' => 1,
    'subscriptions' => ['message_created', 'conversation_status_changed'],
    'secret' => 'wh-secret',
    'inbox' => ['id' => 7, 'name' => 'WhatsApp Sales'],
];
