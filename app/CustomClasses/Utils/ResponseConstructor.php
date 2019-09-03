<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-16 12:14
 */


namespace App\CustomClasses\Utils;


class ResponseConstructor
{
    protected static $resp = [
        'isOK' => false,
        'err_code' => -1
    ];

    protected static $header = [
        'content-type' => 'application/json'
    ];

    public static function SetResponseHeader(string $key, string $value)
    {
        self::$header[$key] = $value;
    }

    public static function GetResponseHeader(string $key = null)
    {
        if (!empty($key)) {
            return self::$header[$key];
        }
        return self::$header;
    }

    /**
     * @param bool $status
     */
    public static function SetStatus(bool $status)
    {
        self::$resp["isOK"] = $status;
    }

    /**
     * @param string $msg
     */
    public static function SetMsg(string $msg)
    {
        self::$resp["msg"] = $msg;
    }

    /**
     * @param string $key
     * @param $value
     */
    public static function SetData(string $key, $value)
    {
        self::$resp[$key] = $value;
    }

    /**
     * @param string|null $key
     * @return array|mixed
     */
    public static function GetResponse(string $key = null)
    {
        if (!empty($key)) {
            return self::$resp[$key];
        }
        return self::$resp;
    }

    /**
     * @param bool $auto_packaging
     * @return false|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed|string
     */
    public static function ResponseToClient(bool $auto_packaging = false)
    {
        if ($auto_packaging) {
            return response(
                self::GetResponse()
            )->withHeaders(
                self::GetResponseHeader()
            );
        }
        return json_encode(self::$resp);
    }

    /**
     * @param boolean $status
     * @param string $msg
     */
    public static function SetStatusAndMsg(bool $status, string $msg = '业务错误')
    {
        self::SetStatus($status);
        self::SetMsg($msg);
    }
}
