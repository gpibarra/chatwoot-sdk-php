<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Reports;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get the summary report grouped by team.
 *
 * `GET /api/v2/accounts/{accountId}/summary_reports/team`
 */
final class GetTeamSummaryReportRequest extends BaseRequest
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
        return "/api/v2/accounts/{$this->accountId}/summary_reports/team";
    }
}
