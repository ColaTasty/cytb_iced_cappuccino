<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 01:43
 */


namespace App;


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
            return $this->SaveAccessToken($accountId);
        }

        $expireTime = strtotime($access_token->expireTime);

        if ($now < $expireTime) {
            return $access_token->accessToken;
        } else {
            return $this->SaveAccessToken($accountId);
        }
    }

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
            ["accessToken"=>$res->access_token,"expireTime"=>date("Y-m-d H:i:s", $now + $res->expires_in)]
        );

        if (!empty($access_token)) {
            return $res->access_token;
        } else {
            return null;
        }
    }
}
