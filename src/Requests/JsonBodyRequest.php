<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Base for requests that send a JSON body.
 */
abstract class JsonBodyRequest extends BaseRequest implements HasBody
{
    use HasJsonBody;

    /**
     * @var array<string, mixed>
     */
    protected array $payload = [];

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
