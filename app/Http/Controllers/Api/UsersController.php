<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(UserRequest $request) {
        $verifyData = \Cache::get($request->verification_key);
        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        if(!hash_equals($verifyData['code'],$request->verification_code)) {
            // 返回401
            //throw new AuthenticationException('验证码错误');
            abort(401,'验证码不正确');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password,
        ]);
        \Cache::forget($request->verification_key);
        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => auth('api')->login($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function me() {

        return new UserResource(auth('api')->user());
        //return $this->response->item($this->user(),new UserTransformer());
    }
}
