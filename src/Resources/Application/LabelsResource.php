<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Label;
use GPIbarra\ChatwootSdk\Requests\Application\Labels\CreateLabelRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Labels\DeleteLabelRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Labels\GetLabelRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Labels\ListLabelsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Labels\UpdateLabelRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Account labels. Bodies are sent under the `label` root key the controller requires.
 */
final class LabelsResource extends AccountScopedResource
{
    /**
     * @return list<Label>
     */
    public function list(): array
    {
        $request = new ListLabelsRequest($this->accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $labelId): Label
    {
        $request = new GetLabelRequest($this->accountId, $labelId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  string  $title  Lowercase letters, digits, `-` and `_` only.
     */
    public function create(string $title, ?string $description = null, ?string $color = null, ?bool $showOnSidebar = null): Label
    {
        $request = new CreateLabelRequest($this->accountId, ['label' => Payload::filter([
            'title' => $title,
            'description' => $description,
            'color' => $color,
            'show_on_sidebar' => $showOnSidebar,
        ])]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `title`, `description`, `color`, `show_on_sidebar`.
     */
    public function update(int $labelId, array $attributes): Label
    {
        $request = new UpdateLabelRequest($this->accountId, $labelId, ['label' => $attributes]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $labelId): void
    {
        $this->sendForNothing(new DeleteLabelRequest($this->accountId, $labelId));
    }
}
