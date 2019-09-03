<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-09-03 18:16
 */


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
use App\CustomClasses\Utils\WechatApi;
use App\WeChatWelcomeToCy;
use Illuminate\Http\Request;

class WeChatWelcomeToCyController extends Controller
{
    public function SubmitInfo(Request $request)
    {
        if (!isset($request->open_id)) {
            ResponseConstructor::SetStatusAndMsg(false, '缺少open_id');
            return ResponseConstructor::ResponseToClient(true);
        }
        $open_id = $request->open_id;

        if (!isset($request->wechat_id)) {
            ResponseConstructor::SetStatusAndMsg(false, '缺少微信号');
            return ResponseConstructor::ResponseToClient(true);
        }
        $wechat_id = $request->wechat_id;

        $gender = WechatApi::GetUserInfo($open_id);
        $gender = json_decode($gender);
        $gender = $gender->sex;

        $user = new WeChatWelcomeToCy();
        $log_res = $user->SubmitInfo($open_id, $wechat_id, $gender);
        switch ($log_res) {
            case 0:
                ResponseConstructor::SetStatusAndMsg(true, '提交成功，请提交朋友圈截图');
                break;
            case 1:
                ResponseConstructor::SetStatusAndMsg(true, '你已经提交过了，请提交朋友圈截图');
                break;
            case 2:
                ResponseConstructor::SetStatusAndMsg(true, '你已经提交过信息了，祝你好运！');
                break;
            case 3:
                ResponseConstructor::SetStatusAndMsg(false, '好像服务器出错了，请向城院小吧吧反馈');
                break;
            default:
                ResponseConstructor::SetStatusAndMsg(false, '未知错误，请向城院小吧吧反馈');
                break;
        }

        return ResponseConstructor::ResponseToClient(true);
    }

    public function SubmitImage(Request $request)
    {
        if (!isset($request->open_id)) {
            ResponseConstructor::SetStatusAndMsg(false, '缺少open_id');
            return ResponseConstructor::ResponseToClient(true);
        }
        $open_id = $request->open_id;

        if (!isset($request->media_id)) {
            ResponseConstructor::SetStatusAndMsg(false, '缺少media_id');
            return ResponseConstructor::ResponseToClient(true);
        }
        $media_id = $request->media_id;

        $user = new WeChatWelcomeToCy();
        $log_flag = $user->SubmitImage($open_id, $media_id);
        switch ($log_flag){
            case 0:
                ResponseConstructor::SetStatusAndMsg(true, '参与成功，请将朋友圈保留至活动开奖，祝你好运！');
                break;
            case 1:
                ResponseConstructor::SetStatusAndMsg(false, '图片上传失败');
                break;
            case 2:
                ResponseConstructor::SetStatusAndMsg(false, '图片保存失败，请向【城院贴吧机器人】添加并联系小吧吧微信');
                break;
            case 3:
                ResponseConstructor::SetStatusAndMsg(false, '你好像还没提交信息，请回复【开学锦鲤】');
                break;
            case 4:
                ResponseConstructor::SetStatusAndMsg(false, '未知数据，请向【城院贴吧机器人】添加并联系小吧吧微信');
                break;
            default:
                ResponseConstructor::SetStatusAndMsg(false, '未知失败，请向【城院贴吧机器人】添加并联系小吧吧微信');
                break;
        }
        return ResponseConstructor::ResponseToClient(true);
    }
}
