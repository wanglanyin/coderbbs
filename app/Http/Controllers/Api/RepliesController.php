<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request,Topic $topic,Reply $reply) {
        /**
         *   $reply->content = $request->content;
         *   $reply->topic()->associate($topic);
         *   $reply->user()->associate($this->user());
         *   $reply->save();
         */
        $reply->fill($request->all());
        $reply->user_id = $this->user()->id;
        $reply->topic_id = $topic->id;
        $reply->save();
        return $this->response->item($reply,new ReplyTransformer());
    }

    public function destroy(Topic $topic,Reply $reply) {
        if ($reply->topic_id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }

    public function index(Topic $topic) {
        $replies = $topic->replies()->paginate(20);
        return $this->response->paginator($replies,new ReplyTransformer());
    }

    public function userIndex(User $user) {
        $replies = $user->replies()->paginate(20);
        return $this->response->paginator($replies,new ReplyTransformer());
    }
}
