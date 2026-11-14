<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\AuditLogs\ListAuditLogsRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Account audit logs (Enterprise edition only; Community installs answer 404).
 */
final class AuditLogsResource extends AccountScopedResource
{
    /**
     * @param  array<string, mixed>  $query  Other filters: `q`, `types`, `since`, `until`, `sort`.
     * @return array<array-key, mixed> `{per_page, total_entries, current_page, audit_logs: [...]}`.
     */
    public function list(?int $page = null, array $query = []): array
    {
        return $this->sendForArray(new ListAuditLogsRequest($this->accountId, Payload::merge(['page' => $page], $query)));
    }
}
