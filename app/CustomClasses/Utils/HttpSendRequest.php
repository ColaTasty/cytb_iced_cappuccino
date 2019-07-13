<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-13 00:23
 */


namespace App\CustomClasses\Utils;


class HttpSendRequest
{
    protected $curl;
    protected $curl_info;
    protected $curl_error;
    protected $method;
    protected $request_url;
    protected $request_data;
    protected $request_cookie;
    protected $response;

    public static function dataArrayToString(array $data)
    {
        return http_build_query($data);
    }

    public static function cookieArrayToString(array $data)
    {
        return http_build_cookie($data);
    }

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function __get($name)
    {
        if (isset($this->$name))
            return $this->$name;
        else
            return null;
    }

    public function sendGet(string $url)
    {
        $this->method = "GET";
        $this->request_url = $url;
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPGET, true);
        return $this;
    }

    public function sendPostString(string $url)
    {
        $this->method = "POST";
        $this->request_url = $url;
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        return $this;
    }

    public function setData(string $data)
    {
        $this->request_data = $data;
        if ($this->method !== "GET")
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        return $this;
    }

    public function setCookie(string $cookie)
    {
        $this->request_cookie = $cookie;
        curl_setopt($this->curl, CURLOPT_COOKIE, $cookie);
        return $this;
    }

    public function send()
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-type:application/json'
        ]);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        //***关闭SSL验证***
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);              // 使用自动跳转
        //***最大等待60s获得响应***
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 60);
        $this->response = curl_exec($this->curl);
        $this->curl_info = curl_getinfo($this->curl);
        if (!$this->response)
            $this->curl_error = curl_error($this->curl);
        curl_close($this->curl);
        return $this->response;
    }
}