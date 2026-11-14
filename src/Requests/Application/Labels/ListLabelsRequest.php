<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Labels;

use GPIbarra\ChatwootSdk\Data\Label;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Enums\Method;
use Saloon\Http\Response;

/**
 * List the account labels.
 *
 * `GET /api/v1/accounts/{accountId}/labels`
 */
final class ListLabelsRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    public function __construct(public readonly int $accountId) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/labels";
    }

    /**
     * @return list<Label>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(Label::fromArray(...), ResponseData::list($response, 'payload'));
    }
}
