<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-03 18:36
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatSessionToken extends Model
{
    protected $table = "WeChatSessionToken";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $fillable = ['openid', 'token', 'wechat_accountid', 'status', 'deadline'];

    public function GetNewToken($openid, $wechat_accountid = 1)
    {
        $time = time();

        $token = md5($openid . $time);

        $res = self::updateOrCreate(
            ["openid"=>$openid,"wechat_accountid"=>$wechat_accountid],
            ["token"=>$token,"status"=>1,"deadline"=>$time + 60]
        );

        return $res;
    }

    public function VerifyToken($token){
        $log = self::where("token",$token)->first();

        $time = time();

        if (empty($log)){
            return null;
        }

        if ($log->status == 1 && $time < $log->deadline){
            $log->status = 0;

            $log->save();

            return $log->openid;
        }
        else{
            return null;
        }
    }
}
