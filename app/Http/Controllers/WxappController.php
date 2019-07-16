<?php


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
use App\CustomClasses\Utils\WxappApi;
use App\Http\Controllers\Wxapp\Cet;
use App\WeChatUserSession;

class WxappController extends Controller
{
    /**
     * 小程序用户登录
     * @param $js_code
     * @return mixed
     */
    public function WxappLogin($js_code = "")
    {
        if (empty($js_code)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("登录码不能为空");
            return response(
                ResponseConstructor::ResponseToClient()
            )->withHeaders(
                ResponseConstructor::GetResponseHeader()
            );
        }

        $res = WxappApi::WxappLogin($js_code);

        $res = json_decode($res);

        if (isset($res->errcode)) {
            $checked = false;
            switch ($res->errcode) {
                case 40163:
                case 40029:
                    ResponseConstructor::SetStatus(false);
                    ResponseConstructor::SetMsg("登录码无效");
                    break;
                case 45011:
                    ResponseConstructor::SetStatus(false);
                    ResponseConstructor::SetMsg("频率限制，每个用户每分钟100次");
                    break;
                case -1:
                    ResponseConstructor::SetStatus(false);
                    ResponseConstructor::SetMsg("系统繁忙");
                    break;
                case 0:
                    $checked = true;
                    break;
                default:
                    ResponseConstructor::SetStatus(false);
                    ResponseConstructor::SetMsg("登录码使用失败");
            }

            if (!$checked) {
                return ResponseConstructor::ResponseToClient(true);
            }
        }

        $user_record = new WeChatUserSession();

        $resp = $user_record->UpdateSession($res->openid, $res->session_key);

        if ($resp) {
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetMsg("登录成功");
            ResponseConstructor::SetData("openId", $res->openid);
        } else {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("登录失败");
            ResponseConstructor::SetData("res", $res);
        }
        return response(
            ResponseConstructor::ResponseToClient()
        )->withHeaders(
            ResponseConstructor::GetResponseHeader()
        );
    }

    public function HomePageFeatures()
    {
        if (strtoupper($_SERVER["REQUEST_METHOD"]) != "POST") {
            return response(view("error", ["msg" => "访问错误", "code" => 404]), 404);
        }
        return response(
            view("wxappFeatures")
        )->withHeaders(
            ResponseConstructor::GetResponseHeader()
        );
    }

    private function ModulesLoad($obj, $method)
    {
        if (method_exists($obj, $method)) {
            return $obj->$method();
        } else {
            return response("访问错误[code : 404]", 404);
        }
    }

    public function Cet($method)
    {
        $cet = new Cet();
        return $this->ModulesLoad($cet, $method);
    }
}
