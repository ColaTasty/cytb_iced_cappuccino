<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-15 01:40
 */


namespace App\Http\Controllers;

use App\CustomClasses\Utils\ResponseConstructor;
use App\Http\Controllers\WeChat\QueryExaminationMail;
use App\WeChatJsApi;
use Illuminate\Http\Request;


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
            return response(view("error", ["msg" => "Identify Failed"]), 404);
        }
    }

    public function PleaseUpdate()
    {
        return view("pleaseUpdate");
    }

    public function QueryExaminationMail($ticket = "")
    {
        if (empty($ticket) && !isset($_POST["ticket"])) {
            return view("wechat.queryExaminationMail.form");
        }

        if (isset($_POST["ticket"])) {
            $ticket = $_POST["ticket"];
        }

        $query = new QueryExaminationMail();

        $res = $query->query($ticket);

        return view("wechat.queryExaminationMail.result", ["res" => $res]);
    }

    public function GetJsConfig(Request $request)
    {
        if (!isset($request->url)) {
            return response(view("error"));
        }

        $url = $request->url;

        $js_api = new WeChatJsApi();

        $js_config = $js_api->GetJsConfig(3, $url);
        if (empty($js_config)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("jsapi_ticket获取失败");
        }

        ResponseConstructor::SetStatus(true);
        ResponseConstructor::SetData("jsConfig",$js_config);

        return ResponseConstructor::ResponseToClient(true);
    }

    public function NoticeNoWxapp(Request $request){
        return view("wechat.notice",["message"=>"【城院贴吧小助手】开始无限期维护了，小程序功能会逐步重新上线公众号，敬请期待！"]);
    }
}
