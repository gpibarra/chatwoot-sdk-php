<?php

declare(strict_types=1);

// API channel inbox seen by a non-administrator token: app/views/api/v1/models/_inbox.json.jbuilder (v4.18.0)
// renders the API block without `hmac_token`/`secret`, and no `provider_config` exists for this channel.
return [
    'id' => 2,
    'avatar_url' => '',
    'channel_id' => 1,
    'name' => 'Backend',
    'channel_type' => 'Channel::Api',
    'greeting_enabled' => false,
    'greeting_message' => null,
    'working_hours_enabled' => false,
    'enable_email_collect' => true,
    'csat_survey_enabled' => false,
    'enable_auto_assignment' => true,
    'out_of_office_message' => null,
    'working_hours' => [],
    'timezone' => 'UTC',
    'callback_webhook_url' => null,
    'allow_messages_after_resolved' => true,
    'lock_to_single_conversation' => false,
    'sender_name_type' => 'friendly',
    'business_name' => null,
    'messaging_service_sid' => null,
    'phone_number' => null,
    'webhook_url' => 'https://example.test/chatwoot',
    'inbox_identifier' => 'QmFja2VuZA',
    'additional_attributes' => [],
    'provider' => null,
];
