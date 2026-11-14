<?php

declare(strict_types=1);

// app/views/api/v1/models/_agent_bot.json.jbuilder (v4.18.0) as seen by an administrator
// (`access_token` and `secret` are only rendered for administrators).
return [
    'id' => 4,
    'name' => 'Triage bot',
    'description' => 'Routes new conversations',
    'thumbnail' => '',
    'outgoing_url' => 'https://bot.example.test/events',
    'bot_type' => 'webhook',
    'bot_config' => [],
    'account_id' => 1,
    'access_token' => 'bot-token',
    'secret' => 'bot-secret',
    'system_bot' => false,
];
