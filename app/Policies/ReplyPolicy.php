<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy extends Policy
{

    public function destroy(User $user,Reply $reply) {
        //return $user->id == $reply->user_id;
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
