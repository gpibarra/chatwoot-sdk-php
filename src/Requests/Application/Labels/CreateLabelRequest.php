<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Labels;

use GPIbarra\ChatwootSdk\Data\Label;
use GPIbarra\ChatwootSdk\Requests\JsonBodyRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * Create a label.
 *
 * `POST /api/v1/accounts/{accountId}/labels`
 */
final class CreateLabelRequest extends JsonBodyRequest
{
    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $payload  JSON body.
     */
    public function __construct(
        public readonly int $accountId,
        array $payload = [],
    ) {
        $this->payload = $payload;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/labels";
    }

    public function createDtoFromResponse(Response $response): Label
    {
        return Label::fromArray(ResponseData::object($response));
    }
}
