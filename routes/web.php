<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * 网站根
 */
Route::any("/index","IndexController@index");
Route::get("/laravel","IndexController@laravel");
Route::get("/get-active-token/{openid}/{wechat_accountid?}","IndexController@getActiveToken");

/**
 * 测试
 */
Route::prefix("test")->group(function (){
    Route::any("/","IndexController@test");
    Route::get("db","IndexController@testDB");
    Route::get("json","IndexController@testJson");
    Route::get("get","IndexController@testGet");
    Route::get("post","IndexController@testPost");
    Route::get("url-encode","IndexController@testUrlEncode");
    Route::get("form","IndexController@testForm");
    Route::get("acc/{open_id}","IndexController@testAcc");
});

/**
 * 微信小程序
 */
Route::prefix("wxapp")->group(function (){
    // 用户登录
    Route::any("login/{js_code?}","WxappController@WxappLogin");
    // 主页内容
    Route::any("home-page-features","WxappController@HomePageFeatures");
    // 四六级
    Route::any("cet/{method?}/{zkz?}","WxappController@Cet");
    // 验证用户信息
    Route::any("verify-user-info","WxappController@VerifyUserInfo");
    // 解密用户信息
    Route::any("decrypt","WxappController@DecryptSensitiveData");
    // 解析并返回跳转小程序网页
//    Route::any("website","WxappController@Website");
});

/**
 * 微信公众号
 */
Route::prefix("wechat")->group(function (){
//    开发者网站授权
    Route::any("dev-auth","WeChatController@WeChatDevAuth");
//    请升级微信
    Route::any("please-update","WeChatController@PleaseUpdate");
//    查询录取通知书邮件
    Route::any("query-examination-mail/{ticket?}","WeChatController@QueryExaminationMail");
//    七夕活动
    Route::middleware(['user.session.token'])->group(function (){
        Route::prefix("qixi")->group(function (){
            Route::get(
                "default-matching","WeChatQixiController@DefaultMatching"
            );
            Route::get(
                "/start-matching","WeChatQixiController@StartMatching"
            );
            Route::post(
                "/submit-info","WeChatQixiController@SubmitInfo"
            );
            Route::get(
                "/{active_token?}","WeChatQixiController@Index"
            );
            //)->middleware(['qixi','user.session.token']);
        });
    });
//    公众号新名字参与奖
    Route::get("lucky-draw","WeChatController@LuckyDraw");
});
