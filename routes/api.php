<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
],function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.throttling.sign')['limit'],
        'expires' => config('api.throttling.sign')['expires']
    ],function($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('v1.verificationCodes.store');
        //用户注册
        $api->post('users','UsersController@store')->name('v1.users.store');
        //验证码
        $api->post('captchas','CaptchasController@store')->name('v1.captchas.store');
        //第三方登陆
        $api->post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')->where('social_type', 'weixin')->name('v1.socials.authorizations.store');
        //登陆
        $api->post('authorizations','AuthorizationsController@store')->name('v1.authorizations.store');
        //刷新token
        $api->put('authorizations/current','AuthorizationsController@update')
            ->name('authorizations.update');
        //删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('authorizations.destroy');
    });


    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.throttling.access')['limit'],
        'expires' => config('api.throttling.access')['expires']
    ],function($api) {

    });

});

$api->version('v2',function($api) {
    $api->post('users', function () {
        return 'v2';
    });
});
