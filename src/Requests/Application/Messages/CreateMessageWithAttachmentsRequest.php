<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Requests\Application\Messages;

use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Requests\BaseRequest;
use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Data\MultipartValue;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasMultipartBody;

/**
 * Create a message with file attachments (multipart/form-data, `attachments[]` parts).
 *
 * `POST /api/v1/accounts/{accountId}/conversations/{conversationId}/messages`
 */
final class CreateMessageWithAttachmentsRequest extends BaseRequest implements HasBody
{
    use HasMultipartBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, scalar>  $fields  Plain form fields (`content`, `message_type`, `private`, ...).
     * @param  list<MultipartValue>  $attachments  File parts; their name should be `attachments[]`.
     */
    public function __construct(
        public readonly int $accountId,
        public readonly int $conversationId,
        private readonly array $fields,
        private readonly array $attachments,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/api/v1/accounts/{$this->accountId}/conversations/{$this->conversationId}/messages";
    }

    /**
     * @return list<MultipartValue>
     */
    protected function defaultBody(): array
    {
        $parts = [];

        foreach ($this->fields as $name => $value) {
            $parts[] = new MultipartValue($name, is_bool($value) ? ($value ? 'true' : 'false') : (string) $value);
        }

        return [...$parts, ...$this->attachments];
    }

    public function createDtoFromResponse(Response $response): Message
    {
        return Message::fromArray(ResponseData::object($response));
    }
}
