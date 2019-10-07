<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AuthorizationsController extends Controller
{
    public function socialStore($type,AuthorizationRequest $request) {
        $driver = \Socialite::driver($type);

        try{
            if($code = $request->code) {
                $response = $driver->getAccessTokenResponse($code);
                $token = Arr::get($response,'access_token');
            }else {
                $token = $request->access_token;
                if ($type == 'weixin') {
                    //return $request->openid;
                    $driver->setOpenId($request->openid);
                }
            }
            $oauthUser = $driver->userFromToken($token);
            //dd($oauthUser->getId());
            switch($type) {
                case 'weixin':
                    $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

                    if ($unionid) {
                        $user = User::where('weixin_unionid', $unionid)->first();
                    } else {
                        $user = User::where('weixin_openid', $oauthUser->getId())->first();
                    }

                    if (!$user) {
                        $user = User::create([
                            'name' => $oauthUser->getNickname(),
                            'avatar' => $oauthUser->getAvatar(),
                            'weixin_openid' => $oauthUser->getId(),
                            'weixin_unionid' => $unionid,
                        ]);
                    }
                    break;
            }
            //return response()->json(['token' => $user->id]);
            $token= auth('api')->login($user);

            return $this->respondWithToken($token)->setStatusCode(201);
        }catch(\Exception $e) {
            //return response()->json([$e->getMessage()]);
            throw new AuthenticationException('参数错误，未获取用户信息');
        }
    }

    public function store(LoginRequest $request) {
        $username = $request->username;
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;
        $credentials['password'] = $request->password;

        if(!$token = \Auth::guard('api')->attempt($credentials)) {
            abort(401,'用户名或密码错误');
        }

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function update() {
       $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();
        return response(null,204);
    }
}
