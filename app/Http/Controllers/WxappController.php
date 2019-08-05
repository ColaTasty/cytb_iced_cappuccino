<?php


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
use App\CustomClasses\Utils\WxappApi;
use App\Http\Controllers\Wxapp\Cet;
use App\WeChatUser;
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

    private function ModulesLoad($obj, $method, $params = [])
    {
        if (method_exists($obj, $method)) {
            return $obj->$method($params);
        } else {
            return response(view("error"), 404);
        }
    }

    public function Cet($method, $zkz = "")
    {
        $cet = new Cet();
        $params = [
            "zkz" => $zkz
        ];
        return $this->ModulesLoad($cet, $method, $params);
    }

    public function VerifyUserInfo()
    {

        if (strtoupper($_SERVER["REQUEST_METHOD"]) != "POST") {
            return response(view("error"), 404);
        }

        if (!isset($_POST["rawData"]) || !isset($_POST["signature"]) || !isset($_POST["openId"])) {
            ResponseConstructor::SetMsg("传入参数出错");
            return ResponseConstructor::ResponseToClient(true);
        }

        $rawData = $_POST["rawData"];

        $signature = $_POST["signature"];

        $openId = $_POST["openId"];

        $user_session = WeChatUserSession::where("openid", $openId)->first();
        $session_key = $user_session->sessionkey;

        $res = WxappApi::VerifyUserInfo($rawData, $signature, $session_key, $user_info);

        if ($res) {
            $user = new WeChatUser();
            $res = $user->UpdateUserInfo([
                'openId'=>$user_info->openId,
                'weChatAccountId'=>$user_info->weChatAccountId,
                'nickName'=>$user_info->nickName,
                'gender'=>$user_info->gender,
                'language'=>$user_info->language,
                'city'=>$user_info->city,
                'province'=>$user_info->province,
                'country'=>$user_info->country,
                'avatarUrl'=>$user_info->avatarUrl,
                'lastLoginTime'=>$user_info->lastLoginTime,
            ]);
            if (!empty($res)) {
                ResponseConstructor::SetStatus(true);
                ResponseConstructor::SetMsg("数字签名正确，信息完整");
                ResponseConstructor::SetData("userInfo", $user_info);
                return ResponseConstructor::ResponseToClient(true);
            } else {
                ResponseConstructor::SetStatus(true);
                ResponseConstructor::SetMsg("数字签名正确，但是写入错误");
                ResponseConstructor::SetData("userInfo", $user_info);
                return ResponseConstructor::ResponseToClient(true);
            }
        } else {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("数字签名不正确，信息不完整！");
            ResponseConstructor::SetData("callback", ["rawData" => $rawData, "signature" => $signature, "sessionKey" => $session_key]);
            return ResponseConstructor::ResponseToClient(true);
        }
    }

    public function DecryptSensitiveData($closure = false)
    {
        if (strtoupper($_SERVER["REQUEST_METHOD"]) != "POST") {
            return response(view("error"), 404);
        }

        if (!isset($_POST["iv"]) || !isset($_POST["encryptedData"]) || !isset($_POST["openId"])) {
            ResponseConstructor::SetMsg("传入参数出错");
            return ResponseConstructor::ResponseToClient(true);
        }

        $iv = $_POST["iv"];

        $encryptedData = $_POST["encryptedData"];

        $openId = $_POST["openId"];

        $user_session = WeChatUserSession::where("openId", $openId)->first();
        $session_key = $user_session->sessionkey;

        $res = WxappApi::DecryptSensitiveData($encryptedData, $iv, $session_key, $data);

        if ($res == 0) {
            ResponseConstructor::SetData("callback", $data);
            ResponseConstructor::SetStatus(true);
        }
        ResponseConstructor::SetMsg(WxappApi::DecryptSensitiveDataErrorMsg($res));

        if ($closure){
            return ResponseConstructor::GetResponse();
        }

        return ResponseConstructor::ResponseToClient(true);
    }
}
