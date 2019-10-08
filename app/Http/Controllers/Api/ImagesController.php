<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
//use App\Handlers\ImageUpload;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function store(ImageRequest $request,ImageUploadHandler $upload) {
        $user = $this->user();
        $size = $request->type == 'avatar' ? 362 : 1024;
        $result = $upload->save($request->image, Str::plural($request->type), $user->id, $size);
        $image = new Image([
            'path' => $result['path'],
            'type' => $request->type,
            //'user_id' => $user->id
        ]);
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image,new ImageTransformer())->setStatusCode(201);

    }
}
