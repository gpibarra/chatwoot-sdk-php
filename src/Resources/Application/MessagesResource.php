<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\Message;
use GPIbarra\ChatwootSdk\Data\Page;
use GPIbarra\ChatwootSdk\Requests\Application\Messages\CreateMessageRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Messages\CreateMessageWithAttachmentsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Messages\DeleteMessageRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Messages\ListMessagesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Messages\UpdateMessageRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;
use InvalidArgumentException;
use Saloon\Data\MultipartValue;
use Saloon\Http\Connector;

/**
 * Messages of one conversation.
 */
final class MessagesResource extends AccountScopedResource
{
    public function __construct(Connector $connector, int $accountId, protected readonly int $conversationId)
    {
        parent::__construct($connector, $accountId);
    }

    /**
     * Returns up to 20 messages; page backwards with `$before` (a message ID) or forwards with `$after`.
     *
     * @return Page<Message> `meta` holds the conversation context (labels, contact, assignee, last seen).
     */
    public function list(?int $before = null, ?int $after = null): Page
    {
        $request = new ListMessagesRequest($this->accountId, $this->conversationId, Payload::filter([
            'before' => $before,
            'after' => $after,
        ]));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  string  $messageType  `outgoing` or `incoming` (`incoming` only for API inboxes).
     * @param  array<string, mixed>  $extra  `content_type`, `content_attributes`, `template_params`, `source_id`, `echo_id`, ...
     */
    public function create(string $content, string $messageType = 'outgoing', bool $private = false, array $extra = []): Message
    {
        $request = new CreateMessageRequest($this->accountId, $this->conversationId, Payload::merge([
            'content' => $content,
            'message_type' => $messageType,
            'private' => $private,
        ], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Sends a message with files attached (multipart upload).
     *
     * @param  list<string|MultipartValue>  $attachments  Local file paths, or ready-made multipart parts.
     */
    public function createWithAttachments(
        array $attachments,
        ?string $content = null,
        string $messageType = 'outgoing',
        bool $private = false,
    ): Message {
        $parts = [];

        foreach ($attachments as $attachment) {
            if ($attachment instanceof MultipartValue) {
                $parts[] = $attachment;

                continue;
            }

            $handle = is_readable($attachment) ? fopen($attachment, 'rb') : false;

            if ($handle === false) {
                throw new InvalidArgumentException(sprintf('Attachment "%s" is not a readable file.', $attachment));
            }

            $parts[] = new MultipartValue('attachments[]', $handle, basename($attachment));
        }

        $fields = ['message_type' => $messageType, 'private' => $private];

        if ($content !== null) {
            $fields = ['content' => $content] + $fields;
        }

        $request = new CreateMessageWithAttachmentsRequest($this->accountId, $this->conversationId, $fields, $parts);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Updates the delivery status of a message. Only allowed for API inboxes.
     *
     * @param  string  $status  `sent`, `delivered`, `read` or `failed`.
     */
    public function updateStatus(int $messageId, string $status, ?string $externalError = null): Message
    {
        $request = new UpdateMessageRequest($this->accountId, $this->conversationId, $messageId, Payload::filter([
            'status' => $status,
            'external_error' => $externalError,
        ]));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Soft-deletes a message: Chatwoot replaces its content and removes its attachments.
     */
    public function delete(int $messageId): void
    {
        $this->sendForNothing(new DeleteMessageRequest($this->accountId, $this->conversationId, $messageId));
    }
}
