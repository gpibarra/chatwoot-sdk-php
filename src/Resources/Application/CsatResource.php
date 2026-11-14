<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\Csat\DownloadCsatSurveyResponsesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Csat\GetCsatMetricsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Csat\ListCsatSurveyResponsesRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;

/**
 * CSAT survey responses. Not described by the swagger; mapped from
 * `Api::V1::Accounts::CsatSurveyResponsesController` (v4.18.0).
 *
 * Common filters: `since`, `until` (Unix timestamps), `user_ids[]`, `inbox_id`, `team_id`, `rating`, `page`.
 */
final class CsatResource extends AccountScopedResource
{
    /**
     * @param  array<string, mixed>  $query
     * @return array<array-key, mixed>
     */
    public function list(array $query = []): array
    {
        return $this->sendForArray(new ListCsatSurveyResponsesRequest($this->accountId, $query));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<array-key, mixed> `{total_count, ratings_count, total_sent_messages_count}`.
     */
    public function metrics(array $query = []): array
    {
        return $this->sendForArray(new GetCsatMetricsRequest($this->accountId, $query));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return string The CSV report.
     */
    public function download(array $query = []): string
    {
        return $this->connector->send(new DownloadCsatSurveyResponsesRequest($this->accountId, $query))->body();
    }
}
