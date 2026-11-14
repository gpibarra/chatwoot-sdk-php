<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Labels;

use GPIbarra\ChatwootSdk\Data\Label;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Get a label.
 *
 * `GET /api/v1/accounts/{accountId}/labels/{labelId}`
 */
final class GetLabelRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(
        public readonly int $accountId,
        public readonly int $labelId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/labels/{$this->labelId}";
    }

    public function createDtoFromResponse(Response $response): Label
    {
        return Label::fromArray(ResponseData::object($response));
    }
}
