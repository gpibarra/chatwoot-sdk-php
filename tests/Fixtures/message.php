<?php

declare(strict_types=1);

// app/views/api/v1/models/_message.json.jbuilder (v4.18.0); `sender` is the agent's push_event_data.
return [
    'id' => 1001,
    'content' => 'Hello there',
    'inbox_id' => 7,
    'conversation_id' => 15,
    'message_type' => 1,
    'content_type' => 'text',
    'status' => 'sent',
    'content_attributes' => [],
    'created_at' => 1_763_100_500,
    'private' => false,
    'source_id' => null,
    'sender' => ['id' => 5, 'name' => 'Ana Pérez', 'available_name' => 'Ana', 'avatar_url' => '', 'type' => 'user', 'availability_status' => 'online', 'thumbnail' => ''],
];
