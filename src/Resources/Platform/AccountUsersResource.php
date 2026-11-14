<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Platform;

use GPIbarra\ChatwootSdk\Enums\AgentRole;
use GPIbarra\ChatwootSdk\Requests\Platform\AccountUsers\CreateAccountUserRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\AccountUsers\DeleteAccountUserRequest;
use GPIbarra\ChatwootSdk\Requests\Platform\AccountUsers\ListAccountUsersRequest;
use GPIbarra\ChatwootSdk\Resources\Resource;
use Saloon\Http\Connector;

/**
 * Memberships of users in one account.
 */
final class AccountUsersResource extends Resource
{
    public function __construct(Connector $connector, protected readonly int $accountId)
    {
        parent::__construct($connector);
    }

    /**
     * @return array<array-key, mixed> Raw account users (`{account_id, user_id, role, ...}`).
     */
    public function list(): array
    {
        return $this->sendForArray(new ListAccountUsersRequest($this->accountId));
    }

    /**
     * Adds the user to the account, or updates their role when already a member.
     *
     * @return array<array-key, mixed>
     */
    public function create(int $userId, AgentRole|string $role = AgentRole::Agent): array
    {
        return $this->sendForArray(new CreateAccountUserRequest($this->accountId, [
            'user_id' => $userId,
            'role' => $role instanceof AgentRole ? $role->value : $role,
        ]));
    }

    public function delete(int $userId): void
    {
        $this->sendForNothing(new DeleteAccountUserRequest($this->accountId, ['user_id' => $userId]));
    }
}
