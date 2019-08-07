<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 01:43
 */


namespace App;


use App\CustomClasses\Utils\HttpSendRequest;
use App\CustomClasses\Utils\WechatApi;
use Illuminate\Database\Eloquent\Model;

class WeChatAccessToken extends Model
{
    public $timestamps = false;
    protected $table = "WeChatAccessToken";
    protected $primaryKey = "accountId";
    protected $fillable = ["accountId", "accessToken", "expireTime"];

    public function GetAccessToken($accountId)
    {
        $access_token = self::find($accountId);

        $now = time();

        if (empty($access_token)) {
            return $this->SaveAccessTokenFromLocal();
        }

        $expireTime = strtotime($access_token->expireTime);

        if ($now < $expireTime) {
            return $access_token->accessToken;
        } else {
            return $this->SaveAccessTokenFromLocal();
        }
    }

    /**
     * 从本地的微擎获取AccessToken
     * 如果微擎失效了，请用另一个函数
     */
    private function SaveAccessTokenFromLocal(){
//        访问令牌
        $token = "Ye5GdsI2Z_xL-lpTPD95cyTUh8kOm_hA";
//        微擎数据库里面的“城院贴吧Pro”的id
        $id = 2;

        $url = "http://dgcytb.com/access_token.php?token=$token&id=$id";
        $send = new HttpSendRequest();
        $res = $send->sendGet($url)->send();
        $res = json_decode($res);

        if (isset($res->errcode) && $res->errcode != 0){
            return null;
        }

        $access_token = WeChatAccessToken::updateOrCreate(
            ["accountId"=>3],
            ["accessToken"=>$res->token,"expireTime"=>date("Y-m-d H:i:s", $res->expire)]
        );

        if (!empty($access_token)) {
            return $res->access_token;
        } else {
            return null;
        }
    }

    /**
     * 从微信API获取AccessToken
     */
    private function SaveAccessToken($accountId)
    {
        $now = time();

        $res = WechatApi::GetAccessToken();
        $res = json_decode($res);

        if (isset($res->errcode) && $res->errcode != 0){
            return null;
        }

        $access_token = WeChatAccessToken::updateOrCreate(
            ["accountId"=>$accountId],
            ["accessToken"=>$res->access_token,"expireTime"=>date("Y-m-d H:i:s", $now + 7200)]
        );

        if (!empty($access_token)) {
            return $res->access_token;
        } else {
            return null;
        }
    }
}
