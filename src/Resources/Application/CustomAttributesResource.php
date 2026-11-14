<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes\CreateCustomAttributeDefinitionRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes\DeleteCustomAttributeDefinitionRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes\GetCustomAttributeDefinitionRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes\ListCustomAttributeDefinitionsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomAttributes\UpdateCustomAttributeDefinitionRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Custom attribute definitions. Bodies are sent under the `custom_attribute_definition` root key the
 * controller requires.
 */
final class CustomAttributesResource extends AccountScopedResource
{
    /**
     * @param  int|string|null  $attributeModel  `conversation_attribute` (0) or `contact_attribute` (1).
     * @return array<array-key, mixed>
     */
    public function list(int|string|null $attributeModel = null): array
    {
        return $this->sendForArray(new ListCustomAttributeDefinitionsRequest($this->accountId, Payload::filter(['attribute_model' => $attributeModel])));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function get(int $definitionId): array
    {
        return $this->sendForArray(new GetCustomAttributeDefinitionRequest($this->accountId, $definitionId));
    }

    /**
     * @param  int|string  $displayType  `text`, `number`, `currency`, `percent`, `link`, `date`, `list`, `checkbox` (or 0-7).
     * @param  int|string  $attributeModel  `conversation_attribute` (0) or `contact_attribute` (1).
     * @param  array<string, mixed>  $extra  `attribute_description`, `attribute_values`, `regex_pattern`, `regex_cue`.
     * @return array<array-key, mixed>
     */
    public function create(string $displayName, string $key, int|string $displayType, int|string $attributeModel, array $extra = []): array
    {
        return $this->sendForArray(new CreateCustomAttributeDefinitionRequest($this->accountId, [
            'custom_attribute_definition' => Payload::merge([
                'attribute_display_name' => $displayName,
                'attribute_key' => $key,
                'attribute_display_type' => $displayType,
                'attribute_model' => $attributeModel,
            ], $extra),
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function update(int $definitionId, array $attributes): array
    {
        return $this->sendForArray(new UpdateCustomAttributeDefinitionRequest($this->accountId, $definitionId, ['custom_attribute_definition' => $attributes]));
    }

    public function delete(int $definitionId): void
    {
        $this->sendForNothing(new DeleteCustomAttributeDefinitionRequest($this->accountId, $definitionId));
    }
}
