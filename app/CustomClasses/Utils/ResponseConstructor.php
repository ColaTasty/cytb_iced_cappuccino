<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-16 12:14
 */


namespace App\CustomClasses\Utils;



class ResponseConstructor
{
    protected static $resp = [
        "isOK" => false
    ];

    protected static $header = [
        "content-type" => "application/json"
    ];

    public static function SetResponseHeader(string $key,string $value){
        self::$header[$key] = $value;
    }

    public static function GetResponseHeader(string $key = null){
        if (!empty($key)){
            return self::$header[$key];
        }
        return self::$header;
    }

    public static function SetStatus(bool $status){
        self::$resp["isOK"] = $status;
    }

    public static function SetMsg(string $msg){
        self::$resp["msg"] = $msg;
    }

    public static function SetData(string $key,$value){
        self::$resp[$key] = $value;
    }

    public static function GetResponse(string $key = null){
        if (!empty($key)){
            return self::$resp[$key];
        }
        return self::$resp;
    }

    public static function ResponseToClient($auto_packaging = false){
        if ($auto_packaging){
            return response(
                self::GetResponse()
            )->withHeaders(
                self::GetResponseHeader()
            );
        }
        return json_encode(self::$resp);
    }
}
