<?php

namespace App\Policies;

use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\Response;

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
        //
    }

    public function delete(User $user, Session $session): bool
    {
        //
    }
}
