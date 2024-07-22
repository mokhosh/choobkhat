<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\States\Session\Ongoing;
use App\Models\User;

class SessionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Session $session): bool
    {
        //
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Session $session): bool
    {
        return $user->getKey() === $session->user_id;
    }

    public function delete(User $user, Session $session): bool
    {
        return $user->getKey() === $session->user_id;
    }

    public function finish(User $user, Session $session): bool
    {
        return $user->getKey() === $session->user_id &&
            $session->state->equals(Ongoing::class);
    }
}
