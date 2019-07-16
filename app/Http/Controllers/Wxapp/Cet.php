<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-16 16:32
 */


namespace App\Http\Controllers\Wxapp;


use App\CustomClasses\Utils\HttpSendRequest;
use App\CustomClasses\Utils\ResponseConstructor;

class Cet
{
    public function init(){
        $send = new HttpSendRequest();

        $send->sendGet("http://cet.neea.edu.cn/cet/js/data.js");

        $res = $send->send();

        $str = str_replace("var dq=","",$res);
        $str = str_replace(";","",$str);
        $str = str_replace("\n","",$str);

        $res_code = $send->curl_info["http_code"];
//        dd($send->curl_info);
        if ($res_code == 200){
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetMsg("初始数值获取成功");
            ResponseConstructor::SetData("dd",json_decode($str));
        }else{
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("服务器连接失败，可能成绩服务器大姨妈了");
        }
        return response(
            view("wxappCetInit")
        )->withHeaders(
            ResponseConstructor::GetResponseHeader()
        );
    }
}
