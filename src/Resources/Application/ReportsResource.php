<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetAgentSummaryReportRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetChannelSummaryReportRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetConversationMetricsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetFirstResponseTimeDistributionRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetInboxLabelMatrixRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetInboxSummaryReportRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetOutgoingMessagesCountRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetReportsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetReportsSummaryRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\GetTeamSummaryReportRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Reports\ListReportingEventsRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Reports (`/api/v2/accounts/{id}/reports`, `/summary_reports`) and reporting events.
 * Time ranges are Unix timestamps (`since`, `until`).
 */
final class ReportsResource extends AccountScopedResource
{
    /**
     * Enterprise edition only.
     *
     * @param  array<string, mixed>  $query  `page`, `since`, `until`, `inbox_id`, `user_id`, `name`.
     * @return array<array-key, mixed>
     */
    public function reportingEvents(array $query = []): array
    {
        return $this->sendForArray(new ListReportingEventsRequest($this->accountId, $query));
    }

    /**
     * A metric time series.
     *
     * @param  string  $metric  `conversations_count`, `incoming_messages_count`, `outgoing_messages_count`,
     *                          `avg_first_response_time`, `avg_resolution_time`, `resolutions_count`, `reply_time`.
     * @param  string  $type  `account`, `agent`, `inbox`, `label` or `team`.
     * @param  array<string, mixed>  $query  `id`, `group_by`, `business_hours`, `timezone_offset`, ...
     * @return array<array-key, mixed>
     */
    public function metric(string $metric, string $type = 'account', ?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetReportsRequest($this->accountId, Payload::merge([
            'metric' => $metric,
            'type' => $type,
            'since' => $since,
            'until' => $until,
        ], $query)));
    }

    /**
     * @param  array<string, mixed>  $query  `id`, `business_hours`, ...
     * @return array<array-key, mixed>
     */
    public function summary(string $type = 'account', ?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetReportsSummaryRequest($this->accountId, Payload::merge([
            'type' => $type,
            'since' => $since,
            'until' => $until,
        ], $query)));
    }

    /**
     * Live conversation counts: account-wide (`account`) or per agent (`agent`, optionally one `$userId`).
     *
     * @return array<array-key, mixed>
     */
    public function conversationMetrics(string $type = 'account', ?int $userId = null): array
    {
        return $this->sendForArray(new GetConversationMetricsRequest($this->accountId, Payload::filter([
            'type' => $type,
            'user_id' => $userId,
        ])));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<array-key, mixed>
     */
    public function channelSummary(?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetChannelSummaryReportRequest($this->accountId, self::range($since, $until, $query)));
    }

    /**
     * @param  array<string, mixed>  $query  e.g. `business_hours`.
     * @return array<array-key, mixed>
     */
    public function inboxSummary(?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetInboxSummaryReportRequest($this->accountId, self::range($since, $until, $query)));
    }

    /**
     * @param  array<string, mixed>  $query  e.g. `business_hours`.
     * @return array<array-key, mixed>
     */
    public function agentSummary(?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetAgentSummaryReportRequest($this->accountId, self::range($since, $until, $query)));
    }

    /**
     * @param  array<string, mixed>  $query  e.g. `business_hours`.
     * @return array<array-key, mixed>
     */
    public function teamSummary(?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetTeamSummaryReportRequest($this->accountId, self::range($since, $until, $query)));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<array-key, mixed>
     */
    public function firstResponseTimeDistribution(?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetFirstResponseTimeDistributionRequest($this->accountId, self::range($since, $until, $query)));
    }

    /**
     * @param  array<string, mixed>  $query  `inbox_ids[]`, `label_ids[]`.
     * @return array<array-key, mixed>
     */
    public function inboxLabelMatrix(?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetInboxLabelMatrixRequest($this->accountId, self::range($since, $until, $query)));
    }

    /**
     * @param  string  $groupBy  `agent`, `team`, `inbox` or `label`.
     * @param  array<string, mixed>  $query
     * @return array<array-key, mixed>
     */
    public function outgoingMessagesCount(string $groupBy, ?int $since = null, ?int $until = null, array $query = []): array
    {
        return $this->sendForArray(new GetOutgoingMessagesCountRequest($this->accountId, self::range($since, $until, ['group_by' => $groupBy] + $query)));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    private static function range(?int $since, ?int $until, array $query): array
    {
        return Payload::merge(['since' => $since, 'until' => $until], $query);
    }
}
