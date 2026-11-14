<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Reports;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get the first response time distribution by channel.
 *
 * `GET /api/v2/accounts/{accountId}/reports/first_response_time_distribution`
 */
final class GetFirstResponseTimeDistributionRequest extends BaseRequest
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
        return "/api/v2/accounts/{$this->accountId}/reports/first_response_time_distribution";
    }
}
