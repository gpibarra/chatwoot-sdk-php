<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\CannedResponses\CreateCannedResponseRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CannedResponses\DeleteCannedResponseRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CannedResponses\ListCannedResponsesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\CannedResponses\UpdateCannedResponseRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Canned responses. Bodies are sent under the `canned_response` root key the controller requires.
 */
final class CannedResponsesResource extends AccountScopedResource
{
    /**
     * @return array<array-key, mixed>
     */
    public function list(?string $search = null): array
    {
        return $this->sendForArray(new ListCannedResponsesRequest($this->accountId, Payload::filter(['search' => $search])));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function create(string $shortCode, string $content): array
    {
        return $this->sendForArray(new CreateCannedResponseRequest($this->accountId, [
            'canned_response' => ['short_code' => $shortCode, 'content' => $content],
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes  `short_code`, `content`.
     * @return array<array-key, mixed>
     */
    public function update(int $cannedResponseId, array $attributes): array
    {
        return $this->sendForArray(new UpdateCannedResponseRequest($this->accountId, $cannedResponseId, ['canned_response' => $attributes]));
    }

    public function delete(int $cannedResponseId): void
    {
        $this->sendForNothing(new DeleteCannedResponseRequest($this->accountId, $cannedResponseId));
    }
}
