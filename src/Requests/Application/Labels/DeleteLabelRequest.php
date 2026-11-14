<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Labels;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Delete a label.
 *
 * `DELETE /api/v1/accounts/{accountId}/labels/{labelId}`
 */
final class DeleteLabelRequest extends BaseRequest
{
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $accountId,
        public readonly int $labelId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/labels/{$this->labelId}";
    }
}
