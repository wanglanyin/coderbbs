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
    'middleware' => ['serializer:array','bindings']
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
            ->name('v1.authorizations.update');
        //删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('v1.authorizations.destroy');
    });


    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.throttling.access')['limit'],
        'expires' => config('api.throttling.access')['expires']
    ],function($api) {
        /**
         * 游客可访问接口
         */
        $api->get('categories','CategoriesController@index')->name('v1.categories.index');
        //话题列表
        $api->get('topics', 'TopicsController@index')
            ->name('v1.topics.index');
        //用户发表的话题
        $api->get('users/{user}/topics','TopicsController@userIndex')
            ->name('v1.users.topic.index');
        //话题详情
        $api->get('topics/{topic}','TopicsController@show')
            ->name('v1.topics.show');
        //话题回复列表
        $api->get('topics/{topic}/replies','RepliesController@index')
            ->name('v1.topics.replies.index');
        //用户回复列表
        $api->get('users/{user}/replies','RepliesController@userIndex')
            ->name('v1.users.replies.index');
        // 资源推荐
        $api->get('links', 'LinksController@index')
            ->name('api.links.index');

        /**
         * token访问
         */
        $api->group([
            'middleware' => 'api.auth'
        ],function($api) {
            $api->delete('topics/{topic}/replies/{reply}','RepliesController@destroy')
                ->name('v1.topics.reply.destroy');
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('v1.user.show');
            //图片资源
            $api->post('images','ImagesController@store')
                ->name('v1.images.store');
            //编辑用户信息
            $api->patch('users','UsersController@update')
                ->name('v1.users.update');
            //添加话题
            $api->post('topics','TopicsController@store')
                ->name('v1.topics.store');
            //编辑话题
            $api->patch('topics/{topic}','TopicsController@update')
                ->name('v1.topics.update');
            //删除话题
            $api->delete('topics/{topic}','TopicsController@destroy')
                ->name('v1.topics.destroy');
            //添加回复
            $api->post('topics/{topic}/replies','RepliesController@store')
                ->name('v1.topics.replies.store');
            //通知消息列表
            $api->get('user/notifications','NotificationsController@index')
                ->name('v1.user.notifications.index');
            //通知统计
            $api->get('user/notifications/stats','NotificationsController@stats')
                ->name('v1.user.notifications.stats');
            //标记消息为已读
            $api->patch('user/read/notifications', 'NotificationsController@read')
                ->name('v1.user.notifications.read');
            //当前登陆用户权限
            $api->get('user/permissions','PermissionsController@index')
                ->name('v1.user.permissions.index');
        });
    });

});

$api->version('v2',function($api) {
    $api->post('users', function () {
        return 'v2';
    });
});
