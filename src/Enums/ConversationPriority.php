<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Enums;

enum ConversationPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';
}
