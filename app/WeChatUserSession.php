<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Create On 2019-07-16 12:08
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatUserSession extends Model
{
    public $timestamps = false;
    protected $table = "WeChatUserSession";
    protected $primaryKey = "id";

    /**
     * @param $openid
     * @param $sessionkey
     * @return bool
     */
    public function UpdateSession($openid,$sessionkey){
        if (!$this->IsUserExist($openid)){

            $user_record = new WeChatUserSession();

            $user_record->openid = $openid;
            $user_record->sessionkey = $sessionkey;
            $user_record->updatetime = date("Y-m-d H:i:s");
        }else{

            $user_record = WeChatUserSession::where("openid",$openid)->first();

            $user_record->sessionkey = $sessionkey;
            $user_record->updatetime = date("Y-m-d H:i:s");
        }

        return $user_record->save();
    }

    /**
     * @param $openid
     * @return bool
     */
    private function IsUserExist(string $openid){
        $user = WeChatUserSession::where("openid",$openid);
        if (empty($user)){
            return false;
        }else{
            return true;
        }
    }
}
