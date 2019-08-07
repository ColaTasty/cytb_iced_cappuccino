<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 15:50
 */


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
use App\CustomClasses\Utils\WechatApi;
use App\WeChatJsApi;
use App\WeChatQixiMatchingResult;
use App\WeChatQixiUser;
use Illuminate\Http\Request;

class WeChatQixiController extends Controller
{
    public function Index($active_token = "")
    {
        if (empty($active_token) && empty(session("open_id", null))) {
            return response(view("error", ["msg" => "链接无效"]));
        }
        return response(view("wechat.qixi.index"));
    }

    public function DefaultMatching(Request $request)
    {
        $open_id = session("open_id");

//        view_code访问页面
        if (isset($request->view_code)) {
            $view_code = $request->view_code;

            $result = WeChatQixiMatchingResult::where("view_code", $view_code)->where("open_id", $open_id)->first();
//            不存在的code
            if (empty($result)) {
                return response(view("error", ["msg" => "不存在的访问值"]));
            }

            #region            超时拒绝  废弃
            /*
            $now = time();

            $expire = strtotime($result->updated_at);
            $expire = $expire + 60 * 60 * 2;

            $other_open_id = $result->other_open_id;
            $other_result = WeChatQixiMatchingResult::where("open_id", $other_open_id)->first();

            if ($expire < $now && $result->status == 1) {
                if (empty($other_result) || $other_result->status == 0) {
                    return response(view("error", ["msg" => "匹配结果已失效"]), 404);
                }
            }
            */
            #endregion

            return response(view("wechat.qixi.matchingResult", ["result" => $result]));
        }
//        默认页面
        return response(view("wechat.qixi.defaultMatching", ["matching" => false]));
    }

    public function StartMatching()
    {
        $open_id = session("open_id");

        $user = WeChatQixiUser::where("open_id",$open_id)->first();

        if (empty($user)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("你还没有上传匹配信息");
        } else {
            $matching = new WeChatQixiMatchingResult();
            $result = $matching->Matching($open_id);
            if (empty($result)) {
                ResponseConstructor::SetStatus(true);
                ResponseConstructor::SetMsg("暂时不能帮你匹配到，请晚些再来");
            } else {
                ResponseConstructor::SetStatus(true);
                ResponseConstructor::SetMsg("加入匹配成功！");
                ResponseConstructor::SetData("viewCode", $result->view_code);
            }
        }
        return ResponseConstructor::ResponseToClient(true);
    }

    public function WantMatching(Request $request)
    {
        $open_id = session("open_id");

        $view_code = $request->view_code;

        $result = WeChatQixiMatchingResult::where("view_code", $view_code)->where("open_id", $open_id)->first();
//        不存在的code
        if (empty($result)) {
            return response(view("error", ["msg" => "请检查网址"]));
        }

        $other_open_id = $result->other_open_id;
        $other_result = $result->WantMatching($open_id, $other_open_id);
//        另一位code保存失败
        if (empty($other_result)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("提醒另一位失败了，请重试");
            return ResponseConstructor::ResponseToClient(true);
        }

        $self_user = WeChatQixiUser::where("open_id", $open_id)->first();
        $other_url = "https://makia.dgcytb.com/wechat/qixi/default-matching/" . $other_result->view_code;
        if ($other_result->status == 1)
            $res = WechatApi::SendTextCustomNotice($other_open_id, "你和{$self_user->name}的匹配成功，快进来看看！\n <a href='$other_url'>点击进入</a>");
        else
            $res = WechatApi::SendTextCustomNotice($other_open_id, $self_user->name . "想和你交换信息！\n <a href='$other_url'>点击进入</a>");
//        提醒失败
        if (!$res) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("提醒TA失败了，请重试");
            return ResponseConstructor::ResponseToClient(true);
        }

        $result->status = 1;
//        保存结果
        if ($result->save()) {
            $url = "https://makia.dgcytb.com/wechat/qixi/default-matching/$request->view_code";
            ResponseConstructor::SetStatus(true);
            WechatApi::SendTextCustomNotice($open_id, "你发起了一个查看信息请求\n <a href='{$url}'>查看结果</a>");
            ResponseConstructor::SetMsg("我们已经提醒TA了\n你如果2个小时之内没有收到任何提醒\n可能是TA拒绝了");
        } else {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("未知错误");
        }

        return ResponseConstructor::ResponseToClient(true);
    }

    public function SubmitInfo(Request $request)
    {
//        debug
        $debug = false;

        $open_id = session("open_id");

        $user = new WeChatQixiUser();

        $image = $request->image;
        $image = json_decode($image);
        $image = $image->content;
        if ($debug){
            $dd = var_export($image,true);
            file_put_contents(__DIR__."/submit_info_test_".date("Y-m-d H-i-s").".txt",$dd);
        }

        $saved = $user->SaveImageFromWeChat($open_id, $image);
        if (!$saved) {
            switch ($saved) {
                default:
                    ResponseConstructor::SetMsg("文件上传未知原因失败");
                    break;
                case 1:
                    ResponseConstructor::SetMsg("文件上传失败");
                    break;
                case 2:
                    ResponseConstructor::SetMsg("上传的文件格式不正确");
                    break;
                case 3:
                    ResponseConstructor::SetMsg("文件保存出错");
                    break;
            }
            return ResponseConstructor::ResponseToClient(true);
        }

        $name = $_POST["name"];

        $contact = $_POST["contact"];

        $gender = $_POST["gender"];

        $description = $_POST["description"];

        $user = $user->Insert($open_id, $name, $contact, $gender, $description);

        if (!empty($user)) {
            $user->status = 1;
            if ($user->save()) {
                ResponseConstructor::SetStatus(true);

                $result = new WeChatQixiMatchingResult();

                $result = $result->Matching($open_id);
                if (empty($result)) {
                    ResponseConstructor::SetMsg("加入匹配成功，但是暂时不能为你匹配\n请晚些时候来！");
                } else {
                    ResponseConstructor::SetData("viewCode", $result->view_code);
                    ResponseConstructor::SetMsg("加入匹配成功");
                }
            } else {
                ResponseConstructor::SetStatus(false);
                ResponseConstructor::SetMsg("加入匹配失败，请稍后重试");
            }
        } else {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("信息录入出错了，请重试");
        }

        return ResponseConstructor::ResponseToClient(true);
    }
}
