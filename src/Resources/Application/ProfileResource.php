<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Data\User;
use GPIbarra\ChatwootSdk\Requests\Application\Profile\GetProfileRequest;
use GPIbarra\ChatwootSdk\Requests\Application\Profile\UpdateProfileRequest;
use GPIbarra\ChatwootSdk\Resources\Resource;

/**
 * The profile of the user that owns the access token.
 */
final class ProfileResource extends Resource
{
    public function get(): User
    {
        $request = new GetProfileRequest;

        return $request->createDtoFromResponse($this->connector->send($request));
    }

    /**
     * @param  array<string, mixed>  $attributes  `name`, `display_name`, `email`, `message_signature`, `phone_number`,
     *                                            `ui_settings`, or `current_password` + `password` + `password_confirmation`.
     *                                            Sent under the `profile` root key the controller requires.
     */
    public function update(array $attributes): User
    {
        $request = new UpdateProfileRequest(['profile' => $attributes]);

        return $request->createDtoFromResponse($this->connector->send($request));
    }
}
