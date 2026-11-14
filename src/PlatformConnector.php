<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk;

use GPIbarra\ChatwootSdk\Exceptions\ChatwootException;
use GPIbarra\ChatwootSdk\Resources\Platform\AccountsResource;
use GPIbarra\ChatwootSdk\Resources\Platform\AccountUsersResource;
use GPIbarra\ChatwootSdk\Resources\Platform\AgentBotsResource;
use GPIbarra\ChatwootSdk\Resources\Platform\UsersResource;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

/**
 * Connector for Chatwoot's Platform API (`/platform/api/v1/...`).
 *
 * Authenticates with a platform app token (Super Admin → Platform Apps), sent in the
 * `api_access_token` header. A platform app can only manage the objects it created or was granted.
 */
class PlatformConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    /**
     * @param  string  $baseUrl  Chatwoot installation root, e.g. `http://chatwoot:3000` (no `/platform` suffix).
     */
    public function __construct(
        protected readonly string $baseUrl,
        #[\SensitiveParameter]
        protected readonly string $platformAppToken,
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
            'api_access_token' => $this->platformAppToken,
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

    public function accountUsers(int $accountId): AccountUsersResource
    {
        return new AccountUsersResource($this, $accountId);
    }

    public function agentBots(): AgentBotsResource
    {
        return new AgentBotsResource($this);
    }

    public function users(): UsersResource
    {
        return new UsersResource($this);
    }
}
