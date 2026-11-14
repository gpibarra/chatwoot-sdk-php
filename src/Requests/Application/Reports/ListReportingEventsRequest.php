<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Reports;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * List the account reporting events (Enterprise edition).
 *
 * `GET /api/v1/accounts/{accountId}/reporting_events`
 */
final class ListReportingEventsRequest extends BaseRequest
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
        return "/api/v1/accounts/{$this->accountId}/reporting_events";
    }
}
