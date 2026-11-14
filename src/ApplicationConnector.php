<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk;

use GPIbarra\ChatwootSdk\Exceptions\ChatwootException;
use GPIbarra\ChatwootSdk\Resources\Application\AccountsResource;
use GPIbarra\ChatwootSdk\Resources\Application\AgentBotsResource;
use GPIbarra\ChatwootSdk\Resources\Application\AgentsResource;
use GPIbarra\ChatwootSdk\Resources\Application\AuditLogsResource;
use GPIbarra\ChatwootSdk\Resources\Application\AutomationRulesResource;
use GPIbarra\ChatwootSdk\Resources\Application\CampaignsResource;
use GPIbarra\ChatwootSdk\Resources\Application\CannedResponsesResource;
use GPIbarra\ChatwootSdk\Resources\Application\ContactsResource;
use GPIbarra\ChatwootSdk\Resources\Application\ConversationsResource;
use GPIbarra\ChatwootSdk\Resources\Application\CsatResource;
use GPIbarra\ChatwootSdk\Resources\Application\CustomAttributesResource;
use GPIbarra\ChatwootSdk\Resources\Application\CustomFiltersResource;
use GPIbarra\ChatwootSdk\Resources\Application\HelpCenterResource;
use GPIbarra\ChatwootSdk\Resources\Application\InboxesResource;
use GPIbarra\ChatwootSdk\Resources\Application\IntegrationsResource;
use GPIbarra\ChatwootSdk\Resources\Application\LabelsResource;
use GPIbarra\ChatwootSdk\Resources\Application\MessagesResource;
use GPIbarra\ChatwootSdk\Resources\Application\ProfileResource;
use GPIbarra\ChatwootSdk\Resources\Application\ReportsResource;
use GPIbarra\ChatwootSdk\Resources\Application\TeamsResource;
use GPIbarra\ChatwootSdk\Resources\Application\WebhooksResource;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

/**
 * Connector for Chatwoot's Application API (`/api/v1/...` and `/api/v2/...`).
 *
 * Authenticates with a user or agent-bot access token sent in the `api_access_token` header
 * (Profile settings → Access Token in the Chatwoot dashboard).
 */
class ApplicationConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    /**
     * @param  string  $baseUrl  Chatwoot installation root, e.g. `http://chatwoot:3000` (no `/api` suffix).
     */
    public function __construct(
        protected readonly string $baseUrl,
        #[\SensitiveParameter]
        protected readonly string $apiAccessToken,
    ) {}

    public function resolveBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }

    /**
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'api_access_token' => $this->apiAccessToken,
        ];
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return ChatwootException::fromResponse($response, $senderException);
    }

    public function accounts(): AccountsResource
    {
        return new AccountsResource($this);
    }

    public function profile(): ProfileResource
    {
        return new ProfileResource($this);
    }

    public function agentBots(int $accountId): AgentBotsResource
    {
        return new AgentBotsResource($this, $accountId);
    }

    public function agents(int $accountId): AgentsResource
    {
        return new AgentsResource($this, $accountId);
    }

    public function auditLogs(int $accountId): AuditLogsResource
    {
        return new AuditLogsResource($this, $accountId);
    }

    public function automationRules(int $accountId): AutomationRulesResource
    {
        return new AutomationRulesResource($this, $accountId);
    }

    public function campaigns(int $accountId): CampaignsResource
    {
        return new CampaignsResource($this, $accountId);
    }

    public function cannedResponses(int $accountId): CannedResponsesResource
    {
        return new CannedResponsesResource($this, $accountId);
    }

    public function contacts(int $accountId): ContactsResource
    {
        return new ContactsResource($this, $accountId);
    }

    public function conversations(int $accountId): ConversationsResource
    {
        return new ConversationsResource($this, $accountId);
    }

    public function csat(int $accountId): CsatResource
    {
        return new CsatResource($this, $accountId);
    }

    public function customAttributes(int $accountId): CustomAttributesResource
    {
        return new CustomAttributesResource($this, $accountId);
    }

    public function customFilters(int $accountId): CustomFiltersResource
    {
        return new CustomFiltersResource($this, $accountId);
    }

    public function helpCenter(int $accountId): HelpCenterResource
    {
        return new HelpCenterResource($this, $accountId);
    }

    public function inboxes(int $accountId): InboxesResource
    {
        return new InboxesResource($this, $accountId);
    }

    public function integrations(int $accountId): IntegrationsResource
    {
        return new IntegrationsResource($this, $accountId);
    }

    public function labels(int $accountId): LabelsResource
    {
        return new LabelsResource($this, $accountId);
    }

    public function messages(int $accountId, int $conversationId): MessagesResource
    {
        return new MessagesResource($this, $accountId, $conversationId);
    }

    public function reports(int $accountId): ReportsResource
    {
        return new ReportsResource($this, $accountId);
    }

    public function teams(int $accountId): TeamsResource
    {
        return new TeamsResource($this, $accountId);
    }

    public function webhooks(int $accountId): WebhooksResource
    {
        return new WebhooksResource($this, $accountId);
    }
}
