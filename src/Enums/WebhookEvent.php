<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Enums;

/**
 * Webhook subscription names accepted by Chatwoot (`Webhook::ALLOWED_WEBHOOK_EVENTS` in
 * `app/models/webhook.rb`, v4.18.0). Anything else makes the create/update call fail with 422.
 */
enum WebhookEvent: string
{
    case ConversationStatusChanged = 'conversation_status_changed';
    case ConversationUpdated = 'conversation_updated';
    case ConversationCreated = 'conversation_created';
    case ContactCreated = 'contact_created';
    case ContactUpdated = 'contact_updated';
    case MessageCreated = 'message_created';
    case MessageUpdated = 'message_updated';
    case WebwidgetTriggered = 'webwidget_triggered';
    case InboxCreated = 'inbox_created';
    case InboxUpdated = 'inbox_updated';
    case ConversationTypingOn = 'conversation_typing_on';
    case ConversationTypingOff = 'conversation_typing_off';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $event): string => $event->value, self::cases());
    }
}
