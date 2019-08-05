<?php


namespace App\Http\Controllers;


use App\CustomClasses\Utils\WechatApi;
use App\WeChatAccessToken;
use App\WeChatSessionToken;
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

    public function getActiveToken($openid,$wechat_accountid = 1)
    {
        $log = new WeChatSessionToken();

        $log = $log->GetNewToken($openid,$wechat_accountid);

        return response($log->token);
    }

    public function test()
    {
        echo "test 111";
    }

    public function testJson()
    {
        $arr = [
            "isOK" => true,
            "msg" => "JSON Success",
            "date" => date("Y-m-d H:i:s", time())
        ];
        return json_encode($arr);
    }

    public function testDB()
    {
        $res = DB::select("SELECT * FROM Calendar LIMIT 10");
        foreach ($res as $row) {
            dump($row);
        }
    }

    public function testGet()
    {
        $arr = [
            "isOK" => true,
            "msg" => "Method is GET",
            "date" => date("Y-m-d H:i:s", time())
        ];
        return json_encode($arr);
    }

    public function testPost()
    {
        $arr = [
            "isOK" => true,
            "msg" => "Method is POST",
            "date" => date("Y-m-d H:i:s", time())
        ];
        return json_encode($arr);
    }

    public function testUrlEncode()
    {
        $arr = [
            "isOK" => true,
            "msg" => "Method is POST",
            "date" => date("Y-m-d H:i:s", time())
        ];
        $str = "";
        $arr_idx = 0;
        foreach ($arr as $key => $value) {
            if ($arr_idx > 0)
                $str .= "&";
            $str .= urlencode($key) . "=" . urlencode($value);
            $arr_idx++;
        }
        return $str;
    }

    public function testForm()
    {
        return response(view("test.form"));
    }

    public function testAcc($open_id){
        $t = WechatApi::GetUserInfo($open_id);
        $t = json_decode($t);

        dd($t);

        return "hllll";
    }
}
