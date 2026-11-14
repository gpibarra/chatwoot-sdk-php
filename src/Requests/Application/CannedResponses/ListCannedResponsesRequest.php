<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\CannedResponses;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List canned responses (optionally filtered with `search`).
 *
 * `GET /api/v1/accounts/{accountId}/canned_responses`
 */
final class ListCannedResponsesRequest extends BaseRequest
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string, mixed>  $query  Query string parameters.
     */
    public function __construct(
        public readonly int $accountId,
        array $query = [],
    ) {
        $this->queryParameters = $query;
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/canned_responses";
    }
}
