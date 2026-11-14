<?php

declare(strict_types=1);

// app/views/platform/api/v1/models/_agent_bot.json.jbuilder (v4.18.0): a smaller shape than the
// Application API one (no thumbnail, bot type or system flag; the access token is always present).
return [
    'id' => 30,
    'name' => 'Global bot',
    'description' => null,
    'outgoing_url' => 'https://bot.example.test/global',
    'account_id' => null,
    'access_token' => 'platform-bot-token',
];
