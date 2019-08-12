<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 15:50
 */


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
use App\CustomClasses\Utils\WechatApi;
use App\WeChatJsApi;
use App\WeChatAlertCount;
use App\WeChatQixiFeedback;
use App\WeChatQixiMatchingResult;
use App\WeChatQixiUser;
use Illuminate\Http\Request;

class WeChatQixiController extends Controller
{
    private $admin = [
        "oOyIn0w_bkbAi_o5gHdBctWCDOxc",
        "oOyIn08yQzFguKDnHNQN0atCC-n4",
        "oOyIn0wiCaCmhIZLAQD1dC2PYDtg"
    ];

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

            return response(view("wechat.qixi.matchingResult", ["result" => $result]));
        }
//        默认页面
        return response(view("wechat.qixi.defaultMatching", ["matching" => false]));
    }

    public function StartMatching()
    {
        $open_id = session("open_id");

        $user = WeChatQixiUser::where("open_id", $open_id)->first();

//        不存在用户
        if (empty($user) || $user->status == 0) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("你还没有上传匹配信息");
        } //        用户状态不能匹配
        elseif ($user->status != 1) {
            switch ($user->status) {
                case 2:
                    $msg = "你已被封禁";
                    break;
                case 3:
                    $msg = "请重新提交你的信息";
                    ResponseConstructor::SetStatus(false);
                    ResponseConstructor::SetMsg($msg);
                    return ResponseConstructor::ResponseToClient(true);
                default:
                    $msg = "暂时不能匹配咯，后台正在维护";
                    break;
            }
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetMsg($msg);
        } //        用户正常匹配
        else {
            $matching = new WeChatQixiMatchingResult();
            $result = $matching->Matching($open_id);
//            没有匹配结果
            if (empty($result)) {
                ResponseConstructor::SetStatus(true);
                ResponseConstructor::SetMsg("暂时不能帮你匹配到，请晚些再来");
            } //            有匹配结果
            else {
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
            ResponseConstructor::SetMsg("提醒TA失败，可能想和TA交换信息的人太多了，请再等等");
            return ResponseConstructor::ResponseToClient(true);
        } else {
            $alertCount = new WeChatAlertCount();
            $alertCount->SendNotice($other_open_id);
        }

        $result->status = 1;
//        保存结果
        if ($result->save()) {
            $url = "https://makia.dgcytb.com/wechat/qixi/default-matching/$request->view_code";
            ResponseConstructor::SetStatus(true);
            $res = WechatApi::SendTextCustomNotice($open_id, "你发起了一个查看信息请求\n <a href='{$url}'>查看结果</a>");
            if ($res) {
                $alertCount = new WeChatAlertCount();
                $alertCount->SendNotice($open_id);
            }
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
        if ($debug) {
            $dd = var_export($image, true);
            file_put_contents(__DIR__ . "/submit_info_test_" . date("Y-m-d H-i-s") . ".txt", $dd);
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

    public function Feedback(Request $request)
    {
        $open_id = $request->open_id;

        $view_code = $request->view_code;

        $result = WeChatQixiMatchingResult::where("open_id", $open_id)->where("view_code", $view_code)->first();
        if (empty($result)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("该链接无效，请不要恶意举报");
            return ResponseConstructor::ResponseToClient(true);
        }

        $feedback = new WeChatQixiFeedback();
        $feedback = $feedback->feedback($open_id, $view_code);

        if (empty($feedback)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("现在暂时不能反馈任何信息，请稍后再来");
        } else {
            ResponseConstructor::SetStatus(true);
        }

        return ResponseConstructor::ResponseToClient(true);
    }

    public function SolveFeedback(Request $request)
    {
        $debug = true;

        $open_id = session("open_id");
        #region 是否管理员
        if (!in_array($open_id, $this->admin)) {
            return response(view("error", ["msg" => "你不是管理员"]), 404);
        }
        #endregion
//        访问反馈页
        if (isset($request->feedback_id)) {
            $feedback = WeChatQixiFeedback::find($request->feedback_id);
            if (empty($feedback)) {
                return view("error", ["msg" => "不存在的反馈"]);
            }
            return view("wechat.qixi.solveFeedback", ["feedback" => $feedback]);
        }
//        访问列表
        $feedback_list = WeChatQixiFeedback::all()->toArray();
        return view("wechat.qixi.viewFeedback", ["feedback_list" => $feedback_list]);
    }

    public function SetStatus(Request $request)
    {
        $open_id = session("open_id");

        if (!in_array($open_id, $this->admin)) {
            ResponseConstructor::SetStatusAndMsg(false, "登录态出错，你是管理员吗？");
            return ResponseConstructor::ResponseToClient(true);
        }

        $user_open_id = $request->open_id;
        $user = WeChatQixiUser::where("open_id", $user_open_id)->first();
        if (empty($user)) {
            ResponseConstructor::SetStatusAndMsg(false, "用户不存在");
            return ResponseConstructor::ResponseToClient(true);
        }
        $user->status = $request->status;

        if ($user->save()) {
            ResponseConstructor::SetStatusAndMsg(true, "用户状态修改成功");
            return ResponseConstructor::ResponseToClient(true);
        } else {
            ResponseConstructor::SetStatusAndMsg(false, "用户状态修改失败");
            return ResponseConstructor::ResponseToClient(true);
        }
    }
}
