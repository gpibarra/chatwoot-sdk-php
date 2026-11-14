<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a custom attribute definition.
 *
 * `DELETE /api/v1/accounts/{accountId}/custom_attribute_definitions/{definitionId}`
 */
final class DeleteCustomAttributeDefinitionRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $definitionId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/custom_attribute_definitions/{$this->definitionId}";
    }
}
