<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Exceptions;

use RuntimeException;

/**
 * A successful response whose body does not have the shape the SDK expected.
 */
final class UnexpectedResponseException extends RuntimeException {}
