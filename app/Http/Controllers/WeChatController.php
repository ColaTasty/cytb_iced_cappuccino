<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-15 01:40
 */


namespace App\Http\Controllers;


class WeChatController extends Controller
{
    public function WeChatDevAuth()
    {
        $token = "SupreMakia";
        $signature = isset($_GET["signature"]) ? $_GET["signature"] : null;
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : null;
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : null;
        $echostr = isset($_GET["echostr"]) ? $_GET["echostr"] : null;
        $tmpArr = [$timestamp, $nonce, $token];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr === $signature) {
            return response($echostr);
        } else {
            return response(view("error",["msg"=>"Identify Failed"]),404);
        }
    }

    public function PleaseUpdate(){
        return view("pleaseUpdate");
    }
}
