<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    //
    public function index() {
        $topics = Topic::query()->with(['user','category'])->paginate(30);

        return view('topics.index',compact('topics'));
    }
}
