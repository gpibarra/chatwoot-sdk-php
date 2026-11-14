# gpibarra/chatwoot-sdk-php

Framework-agnostic PHP SDK for [Chatwoot](https://www.chatwoot.com)'s **Application API** and **Platform API**, built on [Saloon](https://docs.saloon.dev) v4.

- Every operation of Chatwoot's official swagger (application and platform tag groups) is covered, plus a few endpoints the swagger omits but the Rails app exposes (inbox deletion, CSAT, help center CRUD, platform account listing...).
- Request bodies and response shapes were checked against the Rails controllers and jbuilder views of **Chatwoot v4.18.0**, not just the swagger, which drifts from reality in several places (see [Swagger vs. Rails](#swagger-vs-rails)).
- Core entities come back as readonly DTOs (`Inbox`, `Webhook`, `Account`, `AgentBot`, `Contact`, `Conversation`, `Message`, `Agent`, `User`, `Team`, `Label`), each keeping the untouched payload in `$raw`; the long tail returns decoded arrays.
- The Client API (public widget API) is out of scope.

## Installation

```sh
composer require gpibarra/chatwoot-sdk-php
```

Requires PHP 8.5+. No framework dependency.

## Quick start

```php
use GPIbarra\ChatwootSdk\ApplicationConnector;
use GPIbarra\ChatwootSdk\Enums\WebhookEvent;

$api = new ApplicationConnector('http://chatwoot:3000', 'your-access-token');

$profile = $api->profile()->get();
$accountId = $profile->accountId;

foreach ($api->inboxes($accountId)->list() as $inbox) {
    echo $inbox->id, ' ', $inbox->name, ' ', $inbox->channelType, ' ', $inbox->phoneNumber ?? '-', PHP_EOL;
}

$inbox = $api->inboxes($accountId)->create('WhatsApp Sales', [
    'type' => 'whatsapp',
    'phone_number' => '+5491100000000',
    'provider' => 'whatsapp_cloud',
    'provider_config' => [
        'api_key' => 'EAAG...',
        'phone_number_id' => '100000000000001',
        'business_account_id' => '200000000000002',
        'app_secret' => '...',
        'webhook_verify_token' => '...',
        'source' => 'manual_setup_v2',
    ],
]);

$webhook = $api->webhooks($accountId)->create(
    'https://bridge.example.com/chatwoot',
    [WebhookEvent::MessageCreated, WebhookEvent::ConversationStatusChanged],
    inboxId: $inbox->id,
    name: 'bridge',
);

$api->inboxes($accountId)->setAgentBot($inbox->id, 4);    // connect
$api->inboxes($accountId)->setAgentBot($inbox->id, null); // disconnect
```

## Connectors

| Connector | Constructor | Auth | Paths |
| --- | --- | --- | --- |
| `ApplicationConnector` | `new ApplicationConnector(string $baseUrl, string $apiAccessToken)` | `api_access_token` header with a user (or agent bot) access token, from *Profile settings → Access token* | `/api/v1/...`, `/api/v2/...` |
| `PlatformConnector` | `new PlatformConnector(string $baseUrl, string $platformAppToken)` | `api_access_token` header with a platform app token, from *Super Admin → Platform Apps* | `/platform/api/v1/...` |

`$baseUrl` is the installation root (`http://chatwoot:3000`, `https://app.chatwoot.com`), without `/api`. Both connectors use Saloon's `AlwaysThrowOnErrors` and `AcceptsJson`, so any non-2xx response throws (see [Error handling](#error-handling)). Since they are regular Saloon connectors you can add middleware, timeouts, retries (`$tries`) or a custom sender as usual.

Account-scoped resources take the account ID: `$api->inboxes(int $accountId)`. Messages are scoped to a conversation: `$api->messages(int $accountId, int $conversationId)`. Conversation and campaign IDs are the account-scoped *display* IDs shown in the dashboard.

Every request also exists as a Saloon request class (`src/Requests/Application/<Area>/*Request.php`, `src/Requests/Platform/<Area>/*Request.php`), so you can send one directly and use `$response->dto()` where a DTO applies:

```php
use GPIbarra\ChatwootSdk\Requests\Application\Inboxes\ListInboxesRequest;

$inboxes = $api->send(new ListInboxesRequest($accountId))->dto(); // list<Inbox>
```

## Conventions

- Common fields are explicit parameters; null optional arguments are not sent. Most write methods end with an `array $extra = []` (or take an `array $attributes`) for anything else Chatwoot accepts. Explicit parameters win over `$extra` on key conflicts.
- Where the Rails controller requires a root key (`params.require(:webhook)`), the SDK sends it wrapped (`{"webhook": {...}}`): webhooks, agents, labels, teams, canned responses, custom attribute definitions, custom filters, integration hooks, campaigns, portals, categories, articles and the profile. Rails' parameter wrapping makes the unwrapped form work only for some of them, so wrapping explicitly is the safe choice.
- Envelopes are unwrapped in DTO mapping: `{payload: [...]}`, `{payload: {webhook: {...}}}`, `{data: {meta, payload}}`, etc.
- Paginated listings (contacts, conversations, messages) return `Data\Page<T>` with `items`, `meta` and `raw`.
- DTO mappers are tolerant: Chatwoot omits keys depending on channel type, permissions and edition, so missing keys become null, `''`, `false` or `[]` instead of errors.

## Resource index

### Application API

| Accessor | Methods |
| --- | --- |
| `accounts()` | `get(int $accountId): Account`, `update(int $accountId, array $attributes): Account` |
| `profile()` | `get(): User`, `update(array $attributes): User` |
| `inboxes($accountId)` | `list(): list<Inbox>`, `get(int $inboxId): Inbox`, `create(string $name, array $channel, array $extra = []): Inbox`, `update(int $inboxId, array $attributes): Inbox`, `delete(int $inboxId): void`, `setAgentBot(int $inboxId, ?int $agentBotId): void`, `agentBot(int $inboxId): ?AgentBot`, `messageTemplates(int $inboxId, ?string $name = null): array`, `members(int $inboxId): list<Agent>`, `addMembers(int $inboxId, array $userIds): list<Agent>`, `updateMembers(int $inboxId, array $userIds): list<Agent>`, `removeMembers(int $inboxId, array $userIds): void`, `brandedEmailLayout(): ?string`, `updateBrandedEmailLayout(?string $layout): ?string` |
| `webhooks($accountId)` | `list(): list<Webhook>`, `create(string $url, array $subscriptions, ?int $inboxId = null, ?string $name = null): Webhook`, `update(int $webhookId, array $attributes): Webhook`, `delete(int $webhookId): void` |
| `agentBots($accountId)` | `list(): list<AgentBot>`, `get(int $agentBotId): AgentBot`, `create(string $name, ?string $outgoingUrl = null, ?string $description = null, array $extra = []): AgentBot`, `update(int $agentBotId, array $attributes): AgentBot`, `delete(int $agentBotId): void` |
| `agents($accountId)` | `list(): list<Agent>`, `create(string $name, string $email, AgentRole\|string $role = AgentRole::Agent, array $extra = []): Agent`, `update(int $agentId, array $attributes): Agent`, `delete(int $agentId): void` |
| `contacts($accountId)` | `list(?int $page, ?string $sort, array $query): Page<Contact>`, `create(?string $name, ?string $email, ?string $phoneNumber, ?string $identifier, ?int $inboxId, array $extra): Contact`, `get(int $contactId, ?bool $includeContactInboxes = null): Contact`, `update(int $contactId, array $attributes): Contact`, `delete(int $contactId): void`, `conversations(int $contactId): list<Conversation>`, `labels(int $contactId): list<string>`, `setLabels(int $contactId, array $labels): list<string>`, `search(string $q, ?int $page, ?string $sort): Page<Contact>`, `filter(array $payload, ?int $page): Page<Contact>`, `createContactInbox(int $contactId, int $inboxId, ?string $sourceId): array`, `contactableInboxes(int $contactId): array`, `destroyCustomAttributes(int $contactId, array $keys): array`, `merge(int $baseContactId, int $mergeeContactId): Contact`, `export(array $columnNames, array $extra): array` |
| `conversations($accountId)` | `meta(array $query): array`, `list(ConversationStatus\|string\|null $status, ?string $assigneeType, ?int $page, array $query): Page<Conversation>`, `create(int $inboxId, ?int $contactId, ?string $sourceId, array $extra): Conversation`, `filter(array $payload, ?int $page): Page<Conversation>`, `get(int $id): Conversation`, `update(int $id, array $attributes): Conversation`, `delete(int $id): void`, `toggleStatus(int $id, ConversationStatus\|string $status, ?int $snoozedUntil): array`, `togglePriority(int $id, ConversationPriority\|string\|null $priority): void`, `toggleTypingStatus(int $id, string $typingStatus, ?bool $isPrivate): void`, `updateLastSeen(int $id): void`, `setCustomAttributes(int $id, array $customAttributes, bool $merge = false): array`, `destroyCustomAttributes(int $id, array $keys): array`, `assignAgent(int $id, ?int $assigneeId): array`, `assignAgentBot(int $id, int $agentBotId): array`, `assignTeam(int $id, ?int $teamId): array`, `assign(int $id, array $attributes): array`, `labels(int $id): list<string>`, `setLabels(int $id, array $labels): list<string>`, `reportingEvents(int $id): array` |
| `messages($accountId, $conversationId)` | `list(?int $before, ?int $after): Page<Message>`, `create(string $content, string $messageType = 'outgoing', bool $private = false, array $extra = []): Message`, `createWithAttachments(array $attachments, ?string $content, string $messageType, bool $private): Message`, `updateStatus(int $messageId, string $status, ?string $externalError): Message`, `delete(int $messageId): void` |
| `labels($accountId)` | `list(): list<Label>`, `get(int $labelId): Label`, `create(string $title, ?string $description, ?string $color, ?bool $showOnSidebar): Label`, `update(int $labelId, array $attributes): Label`, `delete(int $labelId): void` |
| `teams($accountId)` | `list(): list<Team>`, `get`, `create(string $name, ?string $description, ?bool $allowAutoAssign, array $extra): Team`, `update`, `delete`, `members(int $teamId): list<Agent>`, `addMembers`, `updateMembers`, `removeMembers` |
| `auditLogs($accountId)` | `list(?int $page, array $query): array` (Enterprise) |
| `automationRules($accountId)` | `list`, `get`, `create(string $name, string $eventName, array $conditions, array $actions, array $extra)`, `update`, `delete` |
| `campaigns($accountId)` | `list`, `get`, `create(array $attributes)`, `update`, `delete` |
| `cannedResponses($accountId)` | `list(?string $search)`, `create(string $shortCode, string $content)`, `update`, `delete` |
| `csat($accountId)` | `list(array $query)`, `metrics(array $query)`, `download(array $query): string` (CSV) |
| `customAttributes($accountId)` | `list(int\|string\|null $attributeModel)`, `get`, `create(string $displayName, string $key, int\|string $displayType, int\|string $attributeModel, array $extra)`, `update`, `delete` |
| `customFilters($accountId)` | `list(?string $filterType)`, `get`, `create(string $name, string $filterType, array $query)`, `update`, `delete` |
| `helpCenter($accountId)` | `listPortals`, `getPortal`, `createPortal(string $name, string $slug, array $extra)`, `updatePortal`, `deletePortal`, `listCategories`, `getCategory`, `createCategory(string $portalSlug, string $name, string $locale, array $extra)`, `updateCategory`, `deleteCategory`, `listArticles`, `getArticle`, `createArticle(string $portalSlug, string $title, string $content, array $extra)`, `updateArticle`, `deleteArticle` |
| `integrations($accountId)` | `apps()`, `createHook(string $appId, array $settings, ?int $inboxId, array $extra)`, `updateHook`, `deleteHook` |
| `reports($accountId)` | `reportingEvents`, `metric(string $metric, string $type, ?int $since, ?int $until, array $query)`, `summary`, `conversationMetrics(string $type = 'account', ?int $userId)`, `channelSummary`, `inboxSummary`, `agentSummary`, `teamSummary`, `firstResponseTimeDistribution`, `inboxLabelMatrix`, `outgoingMessagesCount(string $groupBy, ...)` |

### Platform API

| Accessor | Methods |
| --- | --- |
| `accounts()` | `list(): list<Account>`, `create(string $name, array $extra = []): Account`, `get(int $accountId): Account`, `update(int $accountId, array $attributes): Account`, `delete(int $accountId): void` |
| `accountUsers($accountId)` | `list(): array`, `create(int $userId, AgentRole\|string $role = AgentRole::Agent): array`, `delete(int $userId): void` |
| `agentBots()` | `list(): list<AgentBot>`, `create(string $name, ?string $outgoingUrl, ?string $description, ?int $accountId, array $extra): AgentBot`, `get`, `update`, `delete` |
| `users()` | `create(string $name, string $email, ?string $password, array $extra): User`, `get`, `update`, `delete`, `loginUrl(int $userId): string`, `token(int $userId): array` |

### Examples

```php
use GPIbarra\ChatwootSdk\Enums\ConversationStatus;
use GPIbarra\ChatwootSdk\PlatformConnector;

// Contacts and conversations
$contact = $api->contacts($accountId)->create(name: 'Juan', phoneNumber: '+5493410000000', inboxId: $inbox->id);
$conversation = $api->conversations($accountId)->create($inbox->id, $contact->id);
$api->messages($accountId, $conversation->id)->create('Hello from the SDK');
$api->conversations($accountId)->setLabels($conversation->id, ['vip']);
$api->conversations($accountId)->toggleStatus($conversation->id, ConversationStatus::Resolved);

$page = $api->conversations($accountId)->list(ConversationStatus::Open, 'unassigned', page: 1);
foreach ($page->items as $item) {
    echo $item->id, ' ', $item->status, PHP_EOL;
}
echo $page->meta['all_count'];

// Attachments
$api->messages($accountId, $conversation->id)->createWithAttachments(['/tmp/invoice.pdf'], 'Your invoice');

// Platform: provision a tenant
$platform = new PlatformConnector('http://chatwoot:3000', 'platform-app-token');
$account = $platform->accounts()->create('Tenant A', ['locale' => 'es']);
$user = $platform->users()->create('Bea', 'bea@example.com', 'S3cret-pass!');
$platform->accountUsers($account->id)->create($user->id, 'administrator');
$ssoUrl = $platform->users()->loginUrl($user->id);
```

## Error handling

Every non-2xx response throws `GPIbarra\ChatwootSdk\Exceptions\ChatwootException` (a Saloon `RequestException`, so `getResponse()`, `getStatus()` and `getPendingRequest()` are available), or one of its subclasses:

| Exception | Status |
| --- | --- |
| `AuthenticationException` | 401, 403 (bad token, missing permission, API access disabled for the account) |
| `NotFoundException` | 404 |
| `ValidationException` | 422 |
| `ChatwootException` | anything else |

Chatwoot error bodies are not uniform (`{"error": "..."}`, `{"message": "...", "attributes": [...]}`, `{"errors": ["..."]}`, `{"error": {"field": ["..."]}}`); all of them are normalised:

```php
use GPIbarra\ChatwootSdk\Exceptions\ChatwootException;
use GPIbarra\ChatwootSdk\Exceptions\ValidationException;

try {
    $api->webhooks($accountId)->create('https://bridge.example.com/chatwoot', ['message_created']);
} catch (ValidationException $e) {
    $e->getErrors();     // ['Url has already been taken']
    $e->getAttributes(); // ['url']
} catch (ChatwootException $e) {
    $e->getStatus();
    $e->getBody();       // decoded error body
}
```

A successful response whose body does not have the expected shape throws `UnexpectedResponseException`.

## Swagger vs. Rails

The vendored swagger was cross-checked against the v4.18.0 Rails source; where they disagree the SDK follows Rails:

- **Webhooks**: list returns `{payload: {webhooks: [...]}}` and create/update return `{payload: {webhook: {...}}}` (swagger: bare array/object). The scoped inbox is exposed as `inbox: {id, name}`, never `inbox_id`. The request accepts `inbox_id` (missing from the swagger) and requires the `webhook` root key.
- **Inboxes**: `DELETE /inboxes/{id}` exists but is not documented. `provider_config` (and other credentials) is only rendered for administrator tokens. `set_agent_bot` with `agent_bot: null` disconnects the bot.
- **Platform agent bots** render only `id, name, description, outgoing_url, account_id, access_token` (swagger lists the Application API shape). **Platform accounts** also render `features`, `limits`, `custom_attributes` and `status`, and `GET /platform/api/v1/accounts` exists but is not documented.
- **Platform account users**: `DELETE` needs `user_id` in the body (swagger documents no body).
- **Custom filters**: the controller reads `filter_type`, not `type`.
- **Integration hooks**: `app_id` is a string identifier (`dialogflow`, `slack`), not an integer.
- **Contacts export**: `POST /contacts/export` (`export()`) exists but is not documented.
- **Agent conversation metrics**: the swagger lists `/reports/conversations` and `/reports/conversations/` as two operations; they are the same endpoint with `type=account|agent`, exposed as `conversationMetrics()`.
- Several write endpoints require a root key the swagger omits (see [Conventions](#conventions)).

## OpenAPI sources

`resources/openapi/application_swagger.json` and `resources/openapi/platform_swagger.json` are vendored, unmodified, from [chatwoot/chatwoot](https://github.com/chatwoot/chatwoot) at tag `v4.19.1` (`swagger/tag_groups/`). [resources/openapi/README.md](resources/openapi/README.md) links each file to the upstream one and explains why that tag while the SDK targets v4.18.0. They are reference material only and are excluded from the Composer dist archive.

## Testing

```sh
composer install
composer test          # Pest
composer analyse       # PHPStan level 8
composer format:check  # Pint
```

Unit tests use Saloon's `MockClient` and assert the method, path, query and JSON body of every endpoint plus the DTO mapping; fixtures live in `tests/Fixtures` and state which jbuilder view their shape comes from.

An optional read-only integration suite (`tests/Integration`) runs against a real Chatwoot when these variables are set, and is skipped otherwise. It never creates, updates or deletes anything:

```sh
CHATWOOT_TEST_URL=http://localhost:3000 CHATWOOT_TEST_TOKEN=... CHATWOOT_TEST_ACCOUNT_ID=1 composer test
```

## License

MIT. See [LICENSE](LICENSE).
