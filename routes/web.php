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

Route::any("/","IndexController@laravel");
Route::get("/laravel","IndexController@laravel");

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
    Route::any("website","WxappController@Website");
});

/**
 * 微信公众号
 */
Route::prefix("wechat")->group(function (){
    Route::any("dev-auth","WeChatController@WeChatDevAuth");
});
