<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Enums;

enum ConversationStatus: string
{
    case Open = 'open';
    case Resolved = 'resolved';
    case Pending = 'pending';
    case Snoozed = 'snoozed';
}
