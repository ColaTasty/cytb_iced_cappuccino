<?php


namespace App\Http\Controllers;


use App\CustomClasses\Utils\WxappApi;
use App\WeChatUser;

class WxappController extends Controller
{
    public function wxappLogin($js_code = ""){
        $res =  WxappApi::wxappLogin($js_code);
        dd($res);
    }
}
