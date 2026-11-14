<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Reports;

use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use Saloon\Enums\Method;

/**
 * Get the conversation counts matrix of inboxes by labels.
 *
 * `GET /api/v2/accounts/{accountId}/reports/inbox_label_matrix`
 */
final class GetInboxLabelMatrixRequest extends BaseRequest
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
        return "/api/v2/accounts/{$this->accountId}/reports/inbox_label_matrix";
    }
}
