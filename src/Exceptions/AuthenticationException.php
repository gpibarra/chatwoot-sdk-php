<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Exceptions;

/**
 * 401 Unauthorized or 403 Forbidden (bad token, missing permission, API access disabled).
 */
final class AuthenticationException extends ChatwootException {}
