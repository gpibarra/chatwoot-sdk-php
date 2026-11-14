<?php

declare(strict_types=1);

// Profile: app/views/api/v1/models/_user.json.jbuilder (v4.18.0), Community edition, no HMAC key configured.
return [
    'access_token' => 'user-token',
    'account_id' => 1,
    'available_name' => 'Ana',
    'avatar_url' => '',
    'confirmed' => true,
    'display_name' => 'Ana',
    'message_signature' => null,
    'email' => 'ana@example.test',
    'id' => 5,
    'inviter_id' => null,
    'name' => 'Ana Pérez',
    'provider' => 'email',
    'pubsub_token' => 'pubsub-token',
    'role' => 'administrator',
    'ui_settings' => [],
    'uid' => 'ana@example.test',
    'type' => 'SuperAdmin',
    'accounts' => [
        [
            'id' => 1,
            'name' => 'Efemaq Lab',
            'status' => 'active',
            'onboarding_step' => null,
            'active_at' => '2026-11-14T10:00:00.000Z',
            'role' => 'administrator',
            'permissions' => ['administrator'],
            'availability' => 'online',
            'availability_status' => 'online',
            'auto_offline' => true,
            'api_and_webhooks' => true,
        ],
    ],
];
