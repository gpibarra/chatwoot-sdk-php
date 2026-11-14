<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Labels;

use GPIbarra\ChatwootSdk\Data\Label;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Update a label.
 *
 * `PATCH /api/v1/accounts/{accountId}/labels/{labelId}`
 */
final class UpdateLabelRequest extends JsonBodyRequest
{
    protected Method $method = Method::PATCH;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $labelId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/labels/{$this->labelId}";
    }

    public function createDtoFromResponse(Response $response): Label
    {
        return Label::fromArray(ResponseData::object($response));
    }
}
