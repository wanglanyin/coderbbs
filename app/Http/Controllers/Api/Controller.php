<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    //
    use Helpers;
    public function errorResponse($statusCode,$message=null,$code=0) {
        throw new HttpException($statusCode, $message, null, [], $code);
    }
}
