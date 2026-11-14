<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Enums;

enum AgentRole: string
{
    case Agent = 'agent';
    case Administrator = 'administrator';
}
