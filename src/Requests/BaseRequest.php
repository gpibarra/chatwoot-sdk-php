<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests;

use Saloon\Http\Request;

/**
 * Base for every Chatwoot request: carries an optional query string supplied by the caller.
 */
abstract class BaseRequest extends Request
{
    /**
     * @var array<string, mixed>
     */
    protected array $queryParameters = [];

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
