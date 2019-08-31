<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-19 22:42
 */


namespace App\Http\Controllers;


class WeChatCETController extends Controller
{
    public function Index(){
        return response(view("wechat.cet.index"));
    }
}
