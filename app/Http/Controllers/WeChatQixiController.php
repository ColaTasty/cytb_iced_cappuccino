<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 15:50
 */


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
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

    public function DefaultMatching()
    {
        $open_id = session("open_id");

        $user = new WeChatQixiUser();

        $matching = $user->IsMatching($open_id);

        return response(view("wechat.qixi.defaultMatching", ["matching" => $matching]));
    }

    public function StartMatching()
    {
        $open_id = session("open_id");

        $user = new WeChatQixiUser();

        if (!$user->HaveInfo($open_id)) {
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("你还没有上传匹配信息");
        } else {
            $user = WeChatQixiUser::find($open_id);
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetMsg("你提交过信息了");
            ResponseConstructor::SetData("msgCode", $user->msg_code);
        }
        return ResponseConstructor::ResponseToClient(true);
    }

    public function SubmitInfo(Request $request)
    {
        $open_id = session("open_id");

        $user = new WeChatQixiUser();

        $image = $request->file("image");

        $saved = $user->SaveImage($open_id, $image);
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

        $gender = $_POST["gender"];

        $description = $_POST["description"];

        $res = $user->Insert($open_id,$name,$gender,$description);

        if ($res){
            $user = WeChatQixiUser::find($open_id);
            ResponseConstructor::SetStatus(true);
            ResponseConstructor::SetData("msgCode",$user->msg_code);
        }
        else{
            ResponseConstructor::SetStatus(false);
            ResponseConstructor::SetMsg("信息录入出错了，请重试");
        }

        return ResponseConstructor::ResponseToClient(true);
    }
}
