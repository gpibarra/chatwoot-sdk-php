<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Data;

use GPIbarra\ChatwootSdk\Support\Cast;

/**
 * An agent bot. The Application API renders `api/v1/models/_agent_bot.json.jbuilder` (thumbnail,
 * bot type/config, `system_bot`; `access_token`/`secret` for administrators only); the Platform API
 * renders a smaller shape (`platform/api/v1/models/_agent_bot.json.jbuilder`) that always includes
 * `access_token`.
 */
final readonly class AgentBot
{
    /**
     * @param  array<array-key, mixed>  $botConfig
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        /** Webhook URL the bot receives events on; hidden for system bots. */
        public ?string $outgoingUrl,
        public ?string $botType,
        public array $botConfig,
        /** Null for global (platform-wide) bots. */
        public ?int $accountId,
        public ?string $accessToken,
        public ?string $secret,
        public ?string $thumbnail,
        public ?bool $systemBot,
        public array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: Cast::int($data, 'id'),
            name: Cast::string($data, 'name'),
            description: Cast::nullableString($data, 'description'),
            outgoingUrl: Cast::nullableString($data, 'outgoing_url'),
            botType: Cast::nullableString($data, 'bot_type'),
            botConfig: Cast::array($data, 'bot_config'),
            accountId: Cast::nullableInt($data, 'account_id'),
            accessToken: Cast::nullableString($data, 'access_token'),
            secret: Cast::nullableString($data, 'secret'),
            thumbnail: Cast::nullableString($data, 'thumbnail'),
            systemBot: Cast::nullableBool($data, 'system_bot'),
            raw: $data,
        );
    }
}
