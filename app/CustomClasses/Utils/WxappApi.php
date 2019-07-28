<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-12 22:42
 */


namespace App\CustomClasses\Utils;


use App\WeChatAccount;

class WxappApi
{
    public static $defaultAccount = 1;
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

    public static function VerifyUserInfo($rawData,$signature,$session_key,&$user_info){
        $signature_2 = sha1($rawData.$session_key);
        if ($signature == $signature_2){
            $user_info = json_decode($rawData);
            return true;
        }else{
            return false;
        }
    }

    public static function DecryptSensitiveData($encryptedData,$iv,$session_key,&$data){

        if (strlen($session_key) != 24) {
            return 40001;
        }
        $aesKey=base64_decode($session_key);


        if (strlen($iv) != 24) {
            return 40002;
        }
        $aesIV=base64_decode($iv);

        $aesCipher=base64_decode($encryptedData);

        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return 40003;
        }

        $app_account = WeChatAccount::find(self::$defaultAccount);
        $app_id = $app_account->appId;
        if( $dataObj->watermark->appid != $app_id )
        {
            return 40003;
        }
        $data = $result;
        return 0;
    }

    public static function DecryptSensitiveDataErrorMsg($errorCode)
    {
        switch ($errorCode) {
            case 40001:
                return "用户登录态可能已过期";
            case 40002:
                return "初始向量不正确！";
            case 40003:
                return "数据不完整或不正确";
            case 0:
                return "数据解析成功";
            default:
                return " 数据解析失败";
        }
    }
}
