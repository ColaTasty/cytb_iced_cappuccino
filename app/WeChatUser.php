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

    public function UpdateUserInfo($openId, $user_info)
    {
        if (!$this->IsExistUser($openId,$user)) {
            $user = new WeChatUser();
        }

        $user->weChatAccountId = WxappApi::$defaultAccount;
        $user->openId = $openId;
        $user->nickName = $user_info->nickName;
        $user->gender = isset($user_info->gender) ? $user_info->gender : -1;
        $user->language = $user_info->language;
        $user->city = isset($user_info->city) ? $user_info->city : "";
        $user->province = isset($user_info->province) ? $user_info->province : "";
        $user->country = isset($user_info->country) ? $user_info->country : "";
        $user->avatarUrl = isset($user_info->avatarUrl) ? $user_info->avatarUrl : "";
        $user->lastLoginTime = date("Y-m-d H:i:s");

        return $user->save();
    }

    public function IsExistUser($openId,&$user){
        $user = WeChatUser::where("openId",$openId)->first();
        if (empty($user)){
            return false;
        }else{
            return true;
        }
    }
}
