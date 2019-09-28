<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends Policy
{
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}
