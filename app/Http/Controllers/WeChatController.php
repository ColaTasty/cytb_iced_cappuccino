<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-15 01:40
 */


namespace App\Http\Controllers;

use App\Http\Controllers\WeChat\QueryExaminationMail;


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

    public function QueryExaminationMail($ticket=""){
        if (empty($ticket) && !isset($_POST["ticket"])){
            return view("wechat.queryExaminationMail.form");
        }

        if (isset($_POST["ticket"])){
            $ticket = $_POST["ticket"];
        }

        $query = new QueryExaminationMail();

        $res = $query->query($ticket);

        return view("wechat.queryExaminationMail.result",["res"=>$res]);
    }

    public function QiXiIndex(){
        return response(view("wechat.qixi.index"));
    }

    public function LuckyDraw(){
        return response("Lucky Draw");
    }
}
