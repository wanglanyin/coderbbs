<?php

namespace App\Observers;

use App\Models\Link;

class LinkObserver
{
    //
    public function saved(Link $link) {
        \Cache::forget($link->cache_key);
    }
}
