<?php


namespace App\Http\Controllers;


use App\CustomClasses\Utils\ResponseConstructor;
use App\CustomClasses\Utils\WechatApi;
use App\WeChatAccessToken;
use App\WeChatAdmin;
use App\WeChatAlertCount;
use App\WeChatSessionToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        return response("你好！", 302, ["Location" => "https://dgcytb.com/wx"]);
    }

    public function laravel()
    {
        return view('welcome');
    }

    public function getActiveToken($open_id, $wechat_accountid = 1)
    {
        $log = new WeChatSessionToken();

        $log = $log->GetNewToken($open_id, $wechat_accountid);

        return response($log->token);
    }

    public function GetAdminToken($open_id)
    {

    }

    public function AddAdmin(Request $request)
    {
        $operator_open_id = $request->operator_open_id;
        $open_id = $request->open_id;
        $level = $request->level;
        $admin = new WeChatAdmin();
        $admin = $admin->AddAdmin($open_id, $level, $operator_open_id);
//        检查操作结果
        if (is_numeric($admin)) {
            switch ($admin) {
                default:
                    ResponseConstructor::SetStatusAndMsg(true, "未知原因出错了");
                    break;
                case 1:
                    ResponseConstructor::SetStatusAndMsg(false, "操作者不是管理员");
                    break;
                case 2:
                    ResponseConstructor::SetStatusAndMsg(false, "操作者管理员期限失效");
                    break;
                case 3:
                    ResponseConstructor::SetStatusAndMsg(false, "操作者权限不够");
                    break;
                case 4:
                    ResponseConstructor::SetStatusAndMsg(false, "未知原因保存失败");
                    break;
            }
        }
        else{
            ResponseConstructor::SetStatusAndMsg(true, "新的管理员添加成功");
        }
        return ResponseConstructor::ResponseToClient(true);
    }
}
