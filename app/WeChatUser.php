<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 */


namespace App;


use App\CustomClasses\Utils\WxappApi;
use Illuminate\Database\Eloquent\Model;

class WeChatUser extends Model
{
//    public $attributes;
    public $timestamps = false;
    protected $table = "WeChatUser";
    protected $primaryKey = "id";
    protected $fillable = ["weChatAccountId","openId","nickName","gender","language","city","province","country","avatarUrl","lastLoginTime"];

    public function UpdateUserInfo($info){
        $res = self::updateOrCreate(
            ["openId" => $info["openId"]],
            [
                "weChatAccountId"=>$info["weChatAccountId"],
                "nickName"=>$info["nickName"],
                "gender"=>$info["gender"],
                "language"=>$info["language"],
                "city"=>$info["city"],
                "province"=>$info["province"],
                "country"=>$info["country"],
                "avatarUrl"=>$info["avatarUrl"],
                "lastLoginTime"=>date("Y-m-d H:i:s"),
            ]
        );

        return $res;
    }
}
