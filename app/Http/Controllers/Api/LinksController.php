<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Transformers\LinkTransformer;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    public function index(Link $link) {
        $lins = $link->getAllCached();
        return $this->response->collection($lins,new LinkTransformer());
    }
}
