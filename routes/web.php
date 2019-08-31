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
Route::any("/index", "IndexController@index");
Route::get("/laravel", "IndexController@laravel");
Route::get("/get-active-token/{open_id}/{wechat_accountid?}", "IndexController@getActiveToken")->middleware('user.refresh.alter');
Route::get("/get-admin-token/{open_id}", "IndexController@getActiveToken")->middleware('user.refresh.alter');
//Route::get("/add-admin/{operator_open_id}/{token}/{level}", "IndexController@AddAdmin")->middleware('user.refresh.alter');
Route::get("/add-admin/{operator_open_id}/{token}{level}", "IndexController@AddAdmin")->middleware('user.refresh.alter');

/**
 * 测试
 */
Route::prefix("test")->group(function () {});

/**
 * 微信小程序
 */
Route::prefix("wxapp")->group(function () {
    #region 用户登录
    Route::any("login/{js_code?}", "WxappController@WxappLogin");
    #endregion
    #region 主页内容
    Route::any("home-page-features", "WxappController@HomePageFeatures");
    #endregion
    #region 四六级
    Route::any("cet/{method?}/{zkz?}", "WxappController@Cet");
    #endregion
    #region 验证用户信息
    Route::any("verify-user-info", "WxappController@VerifyUserInfo");
    #endregion
    #region 解密用户信息
    Route::any("decrypt", "WxappController@DecryptSensitiveData");
    #endregion
    #region 解析并返回跳转小程序网页 弃用
//    Route::any("website","WxappController@Website");
    #endregion
});

/**
 * 微信公众号
 */
Route::prefix("wechat")->group(function () {
    #region    开发者网站授权
    Route::any("dev-auth", "WeChatController@WeChatDevAuth");
    #endregion
    #region    请升级微信
    Route::any("please-update", "WeChatController@PleaseUpdate");
    #endregion
    #region    查询录取通知书邮件
    Route::any("query-examination-mail/{ticket?}", "WeChatController@QueryExaminationMail");
    #endregion
    #region    查询四六级
    Route::prefix("cet")->group(function (){
        Route::get(
            "/", "WeChatCETController@Index"
        );
    });
    #endregion
    #region    七夕活动
    Route::middleware(['qixi','user.session.token','user.check.alter'])->group(function () {
        Route::prefix("qixi")->group(function () {
            Route::get(
                "default-matching/{view_code?}", "WeChatQixiController@DefaultMatching"
            );
            Route::get(
                "start-matching", "WeChatQixiController@StartMatching"
            );
            Route::get(
                "want-matching/{view_code}", "WeChatQixiController@WantMatching"
            );
            Route::post(
                "submit-info", "WeChatQixiController@SubmitInfo"
            );
            Route::get(
                "solve-feedback/{feedback_id?}", "WeChatQixiController@SolveFeedback"
            );
            Route::post(
                "set-status/{open_id}/{status}", "WeChatQixiController@SetStatus"
            );
            Route::get(
                "/{active_token?}", "WeChatQixiController@Index"
            );
        });
    });
    #endregion
    #region    七夕活动(不需验证token)
    Route::prefix("qixi")->group(function () {
        Route::get(
            "/feedback/{open_id}/{view_code}", "WeChatQixiController@Feedback"
        )->middleware('user.refresh.alter');
    });
    #endregion
    Route::any("notice-no-wxapp", "WeChatController@NoticeNoWxapp");
});
