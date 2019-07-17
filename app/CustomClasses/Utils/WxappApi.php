<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-12 22:42
 */


namespace App\CustomClasses\Utils;


use App\WeChatAccount;

class WxappApi
{
    private static $defaultAccount = 1;
    private static $urls = [
        "login" => "https://api.weixin.qq.com/sns/jscode2session?appid=[APPID]&secret=[SECRET]&js_code=[JSCODE]&grant_type=authorization_code"
    ];

    public static function WxappLogin($js_code){
        $res = WeChatAccount::find(self::$defaultAccount);

        $app_id = $res->appId;

        $app_secret = $res->appSecret;

        $tmp_url_id = str_replace("[APPID]",$app_id,self::$urls["login"]);

        $tmp_url_secret = str_replace("[SECRET]",$app_secret,$tmp_url_id);

        $api_url = str_replace("[JSCODE]",$js_code,$tmp_url_secret);

        $send = new HttpSendRequest();

        $api_res =  $send->sendGet($api_url)->send();

        return $api_res;
    }
}
