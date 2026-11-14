<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List custom attribute definitions (filter with `attribute_model`).
 *
 * `GET /api/v1/accounts/{accountId}/custom_attribute_definitions`
 */
final class ListCustomAttributeDefinitionsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        array $query = [],
    ) {
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/custom_attribute_definitions";
    }
}
