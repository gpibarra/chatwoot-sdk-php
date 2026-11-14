<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\CustomFilters\CreateCustomFilterRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomFilters\DeleteCustomFilterRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomFilters\GetCustomFilterRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomFilters\ListCustomFiltersRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CustomFilters\UpdateCustomFilterRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Saved custom filters (per user). Bodies are sent under the `custom_filter` root key the controller
 * requires.
 */
final class CustomFiltersResource extends AccountScopedResource
{
    /**
     * @param  string|null  $filterType  `conversation` (default), `contact` or `report`.
     * @return array<array-key, mixed>
     */
    public function list(?string $filterType = null): array
    {
        return $this->sendForArray(new ListCustomFiltersRequest($this->accountId, Payload::filter(['filter_type' => $filterType])));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function get(int $customFilterId): array
    {
        return $this->sendForArray(new GetCustomFilterRequest($this->accountId, $customFilterId));
    }

    /**
     * The controller reads `filter_type` (the swagger documents it as `type`, which Rails ignores).
     *
     * @param  string  $filterType  `conversation`, `contact` or `report`.
     * @param  array<string, mixed>  $query  The filter definition, e.g. `['payload' => [...]]`.
     * @return array<array-key, mixed>
     */
    public function create(string $name, string $filterType, array $query): array
    {
        return $this->sendForArray(new CreateCustomFilterRequest($this->accountId, [
            'custom_filter' => ['name' => $name, 'filter_type' => $filterType, 'query' => $query],
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes  `name`, `filter_type`, `query`.
     * @return array<array-key, mixed>
     */
    public function update(int $customFilterId, array $attributes): array
    {
        return $this->sendForArray(new UpdateCustomFilterRequest($this->accountId, $customFilterId, ['custom_filter' => $attributes]));
    }

    public function delete(int $customFilterId): void
    {
        $this->sendForNothing(new DeleteCustomFilterRequest($this->accountId, $customFilterId));
    }
}
