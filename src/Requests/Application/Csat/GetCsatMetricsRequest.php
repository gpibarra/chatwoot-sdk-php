<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Csat;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get CSAT metrics (response count, ratings breakdown, surveys sent).
 *
 * `GET /api/v1/accounts/{accountId}/csat_survey_responses/metrics`
 */
final class GetCsatMetricsRequest extends BaseRequest
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
        return "/api/v1/accounts/{$this->accountId}/csat_survey_responses/metrics";
    }
}
