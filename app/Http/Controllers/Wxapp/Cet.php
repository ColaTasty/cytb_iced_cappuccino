<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-16 16:32
 */


namespace App\Http\Controllers\Wxapp;


use App\CetScore;
use App\CustomClasses\Utils\CetApi;
use App\CustomClasses\Utils\HttpSendRequest;
use App\CustomClasses\Utils\ResponseConstructor;

class Cet
{
    public function init()
    {
        $send = new HttpSendRequest();

        $send->sendGet("http://cet.neea.edu.cn/cet/js/data.js");

        $res = $send->send();

        $str = str_replace("var dq=", "", $res);
        $str = str_replace(";", "", $str);
        $str = str_replace("\n", "", $str);

        $res_code = $send->curl_info["http_code"];
//        dd($send->curl_info);
        if ($res_code == 200) {
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetMsg("初始数值获取成功");
            ResponseConstructor::SetData("dd", json_decode($str));
        } else {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("服务器连接失败，可能成绩服务器大姨妈了");
        }
        return response(
            view("wxappCetInit")
        )->withHeaders(
            ResponseConstructor::GetResponseHeader()
        );
    }

    public function verify(array $params)
    {

        if (!isset($_POST["zkz"])) {
            if (!isset($params["zkz"]) || empty($params["zkz"])) {
                return response(view("error"), 404);
            } else {
                $zkz = $params["zkz"];
            }
        } else {
            $zkz = $_POST["zkz"];
        }

        $res = CetApi::CetGetVerifyImage($zkz);
        if ($res == false) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("验证码请求失败，时间未到");
            return ResponseConstructor::ResponseToClient(true);
        } else {
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetMsg("验证码获取成功");
            ResponseConstructor::SetData("callback", $res);
            return ResponseConstructor::ResponseToClient(true);
        }
    }

    public function query()
    {
        if (!isset($_POST["zkz"]) || !isset($_POST["name"]) || !isset($_POST["t"]) || !isset($_POST["cookie"]) || !isset($_POST["v"])) {
            return response(view("error"), 404);
        }

        $zkz = $_POST["zkz"];

        $name = $_POST["name"];

        $t = $_POST["t"];

        $cookie = $_POST["cookie"];

        $v = $_POST["v"];

        $res = CetApi::Query($zkz, $name, $v, $t, $cookie);

        if ($res == false) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("查询失败，成绩服务器连接出错");
            return ResponseConstructor::ResponseToClient(true);
        } else {
            if (isset($res["error"])) {
                ResponseConstructor::SetStatus(false);
                ResponseConstructor::SetMsg("查询失败");
            } else {
                $score_record = new CetScore();

                $record =  $score_record->UpdateScore([
                    "zkz" => $zkz,
                    "name" => $name,
                    "school" => $res["x"],
                    "read" => $res["r"],
                    "write" => $res["w"],
                    "listen" => $res["l"],
                    "total" => $res["s"],
                ]);

                $res["recordSuccess"] = empty($record) ? false:true;

                ResponseConstructor::SetStatus(true);
                ResponseConstructor::SetMsg("查询成功");
            }
            ResponseConstructor::SetData("callback", $res);
            return ResponseConstructor::ResponseToClient(true);
        }
    }
}
