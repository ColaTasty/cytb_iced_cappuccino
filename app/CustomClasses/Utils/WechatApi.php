<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-03 22:45
 */


namespace App\CustomClasses\Utils;


use App\WeChatAccessToken;
use App\WeChatAccount;
use App\WeChatExceptionLog;
use App\WeChatJsApi;

class WechatApi
{
    public static $defaultAccount = 3;
    private static $url = [
        "access_token" => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=[APP_ID]&secret=[APP_SECRET]",
        "user_info" => "https://api.weixin.qq.com/cgi-bin/user/info?access_token=[ACCESS_TOKEN]&openid=[OPEN_ID]&lang=en",
        "custom_notice" => "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=[ACCESS_TOKEN]",
        "jssdk" => "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=[ACCESS_TOKEN]&type=jsapi",
        "media" => "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=[ACCESS_TOKEN]&media_id=[MEDIA_ID]"
    ];

    public static function GetAccessToken()
    {
        $account = WeChatAccount::find(self::$defaultAccount);

        $url = self::$url["access_token"];
        $url = str_replace("[APP_ID]", $account->appId, $url);
        $url = str_replace("[APP_SECRET]", $account->appSecret, $url);

        $send = new HttpSendRequest();

        $res = $send->sendGet($url)->send();

        return $res;
    }

    public static function GetUserInfo($open_id)
    {
        $access_token = new WeChatAccessToken();
        $access_token = $access_token->GetAccessToken(self::$defaultAccount);

        if (empty($access_token)) {
            return null;
        }

        $url = self::$url["user_info"];
        $url = str_replace("[ACCESS_TOKEN]", $access_token, $url);
        $url = str_replace("[OPEN_ID]", $open_id, $url);

        $send = new HttpSendRequest();

        $res = $send->sendGet($url)->send();

        return $res;
    }

    public static function SendTextCustomNotice($openid, $message)
    {
        $data = [
            "touser" => $openid,
            "msgtype" => "text",
            "text" => [
                "content" => "[CONTENT]"
            ]
        ];
        $data = json_encode($data);
        $data = str_replace("[CONTENT]", $message, $data);

        $access_token = new WeChatAccessToken();
        $access_token = $access_token->GetAccessToken(self::$defaultAccount);

        $url = self::$url["custom_notice"];
        $url = str_replace("[ACCESS_TOKEN]", $access_token, $url);

        $send = new HttpSendRequest();

        $res = $send->sendPost($url)
            ->setPostData($data)
            ->send();
        $res = json_decode($res);

        if ($res->errcode == 0) {
            return true;
        } else {
            $res = var_export($res, true);
            $exceLog = new WeChatExceptionLog();
            $exceLog->Log("WechatApi::SendTextCustomNotice()", $res);
            return false;
        }
    }

    public static function GetJsApi($account_id = 0)
    {
        $access_token = new WeChatAccessToken();
        if ($account_id == 0)
            $access_token = $access_token->GetAccessToken(self::$defaultAccount);
        else
            $access_token = $access_token->GetAccessToken($account_id);

        $url = self::$url["jssdk"];
        $url = str_replace("[ACCESS_TOKEN]", $access_token, $url);

        $send = new HttpSendRequest();

        $res = $send->sendGet($url)->send();
        $res = json_decode($res);
        if ($res->errcode != 0) {
            return null;
        }

        return $res->ticket;
    }

    public static function GetJsConfig($account_id, $url, $api_list = null, $debug = false)
    {
        $js_api = new WeChatJsApi();
        return $js_api->GetJsConfig($account_id, $url, $api_list, $debug);
    }

    public static function GetMedia($account_id, $media_id)
    {
        $url = self::$url["media"];

        $access_token = new WeChatAccessToken();
        $access_token = $access_token->GetAccessToken($account_id);

        $url = str_replace("[ACCESS_TOKEN]", $access_token, $url);
        $url = str_replace("[MEDIA_ID]", $media_id, $url);

        $res = file_get_contents($url);

        return $res;
    }
}
