<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Platform;

use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Exceptions\UnexpectedResponseException;
use GPIbarra\ChatwootSdk\Requests\Platform\Users\CreateUserRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Users\CreateUserTokenRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Users\DeleteUserRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Users\GetUserLoginUrlRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Users\GetUserRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\Users\UpdateUserRequest;
use GPIbarra\ChatwootSdk\Resources\Resource;
use GPIbarra\ChatwootSdk\Support\Cast;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Users managed by the platform app.
 */
final class UsersResource extends Resource
{
    /**
     * Creates a confirmed user; an existing user with the same email is reused and attached to the app.
     *
     * @param  array<string, mixed>  $extra  `display_name`, `custom_attributes`.
     */
    public function create(string $name, string $email, ?string $password = null, array $extra = []): User
    {
        $request = new CreateUserRequest(Payload::merge(['name' => $name, 'email' => $email, 'password' => $password], $extra));

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    public function get(int $userId): User
    {
        $request = new GetUserRequest($userId);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Custom attributes are merged into the existing ones; email changes skip reconfirmation.
     *
     * @param  array<string, mixed>  $attributes  `name`, `display_name`, `email`, `password`, `custom_attributes`.
     */
    public function update(int $userId, array $attributes): User
    {
        $request = new UpdateUserRequest($userId, $attributes);

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * Chatwoot answers immediately and deletes the user in a background job.
     */
    public function delete(int $userId): void
    {
        $this->sendForNothing(new DeleteUserRequest($userId));
    }

    /**
     * A one-time single sign-on URL that logs the user into the dashboard.
     */
    public function loginUrl(int $userId): string
    {
        $url = Cast::nullableString($this->sendForArray(new GetUserLoginUrlRequest($userId)), 'url');

        if ($url === null) {
            throw new UnexpectedResponseException('The SSO login response has no "url".');
        }

        return $url;
    }

    /**
     * The user's API access token.
     *
     * @return array<array-key, mixed> `{access_token, expiry, user: {id, name, display_name, email, pubsub_token}}`.
     */
    public function token(int $userId): array
    {
        return $this->sendForArray(new CreateUserTokenRequest($userId));
    }
}
