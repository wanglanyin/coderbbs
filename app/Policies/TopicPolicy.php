<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy extends Policy
{
    public function update(User $user,Topic $topic) {
        //dd($user->isAuthorOf($topic));
        return $user->isAuthorOf($topic);
    }

    public function destroy(User $user,Topic $topic){
        return $user->isAuthorOf($topic);
    }
}
