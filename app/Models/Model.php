<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    //
    public function scopeRecent($query)
    {
        return $query->orderBy('id', 'desc');
    }
}
