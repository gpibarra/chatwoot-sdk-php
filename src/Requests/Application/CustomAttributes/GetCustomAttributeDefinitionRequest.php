<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get a custom attribute definition.
 *
 * `GET /api/v1/accounts/{accountId}/custom_attribute_definitions/{definitionId}`
 */
final class GetCustomAttributeDefinitionRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $definitionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/custom_attribute_definitions/{$this->definitionId}";
    }
}
