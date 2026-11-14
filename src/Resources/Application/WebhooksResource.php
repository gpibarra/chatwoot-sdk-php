<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Webhook;
use GPIbarra\ChatwootSdk\Enums\WebhookEvent;
use GPIbarra\ChatwootSdk\Requests\Application\Webhooks\CreateWebhookRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Webhooks\DeleteWebhookRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Webhooks\ListWebhooksRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Webhooks\UpdateWebhookRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Account webhooks. Bodies are sent under the `webhook` root key, which the Rails controller requires
 * (`params.require(:webhook)`).
 */
final class WebhooksResource extends AccountScopedResource
{
    /**
     * @return list<Webhook>
     */
    public function list(): array
    {
        $request = new ListWebhooksRequest($this->accountId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  list<WebhookEvent|string>  $subscriptions  Event names, see {@see WebhookEvent}.
     * @param  int|null  $inboxId  Restrict deliveries to one inbox; null for the whole account.
     */
    public function create(string $url, array $subscriptions, ?int $inboxId = null, ?string $name = null): Webhook
    {
        $request = new CreateWebhookRequest($this->accountId, [
            'webhook' => Payload::filter([
                'url' => $url,
                'subscriptions' => self::normalizeSubscriptions($subscriptions),
                'inbox_id' => $inboxId,
                'name' => $name,
            ]),
        ]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  Any of `url`, `name`, `subscriptions`, `inbox_id`.
     */
    public function update(int $webhookId, array $attributes): Webhook
    {
        if (isset($attributes['subscriptions']) && is_array($attributes['subscriptions'])) {
            $attributes['subscriptions'] = self::normalizeSubscriptions(array_values($attributes['subscriptions']));
        }

        $request = new UpdateWebhookRequest($this->accountId, $webhookId, ['webhook' => $attributes]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function delete(int $webhookId): void
    {
        $this->sendForNothing(new DeleteWebhookRequest($this->accountId, $webhookId));
    }

    /**
     * @param  list<mixed>  $subscriptions
     * @return list<mixed>
     */
    private static function normalizeSubscriptions(array $subscriptions): array
    {
        return array_map(static fn (mixed $event): mixed => $event instanceof WebhookEvent ? $event->value : $event, $subscriptions);
    }
}
