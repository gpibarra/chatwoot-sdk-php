<?php

declare(strict_types=1);

// WhatsApp Cloud inbox seen by an administrator token. Keys follow app/views/api/v1/models/_inbox.json.jbuilder
// (v4.18.0): common inbox keys, web-widget keys rendered as null (`channel.try`), Twilio `messaging_service_sid`
// and `phone_number`, `provider`, and the WhatsApp block (`message_templates`, `provider_config`,
// `reauthorization_required`). Values are illustrative.
return [
    'id' => 7,
    'avatar_url' => '',
    'channel_id' => 3,
    'name' => 'WhatsApp Sales',
    'channel_type' => 'Channel::Whatsapp',
    'greeting_enabled' => false,
    'greeting_message' => null,
    'working_hours_enabled' => false,
    'enable_email_collect' => true,
    'csat_survey_enabled' => false,
    'csat_config' => [],
    'enable_auto_assignment' => true,
    'auto_assignment_config' => [],
    'out_of_office_message' => null,
    'working_hours' => [],
    'timezone' => 'UTC',
    'callback_webhook_url' => null,
    'allow_messages_after_resolved' => true,
    'lock_to_single_conversation' => false,
    'sender_name_type' => 'friendly',
    'business_name' => null,
    'allowed_domains' => null,
    'widget_color' => null,
    'website_url' => null,
    'hmac_mandatory' => null,
    'welcome_title' => null,
    'welcome_tagline' => null,
    'web_widget_script' => null,
    'website_token' => null,
    'selected_feature_flags' => null,
    'reply_time' => null,
    'messaging_service_sid' => null,
    'phone_number' => '+5491100000000',
    'provider' => 'whatsapp_cloud',
    'message_templates' => [],
    'provider_config' => [
        'api_key' => 'EAAG-test',
        'phone_number_id' => '100000000000001',
        'business_account_id' => '200000000000002',
        'webhook_verify_token' => 'verify-me',
        'source' => 'manual_setup_v2',
    ],
    'reauthorization_required' => false,
];
