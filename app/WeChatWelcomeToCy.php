<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-09-03 18:39
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatWelcomeToCy extends Model
{
    protected $table = "WeChatWelcomeToCy";
    protected $dateFormat = "Y-m-d H:i:s";
    protected $primaryKey = "id";
    protected $fillable = ['open_id', 'wechat_id', 'gender', 'screen_image'];

    public function SubmitInfo($open_id, $wechat_id, $gender)
    {
        $user = self::where('open_id', $open_id)->first();
        if (!empty($user)) {
            if (empty($user->screen_iamge)){
                return 1;
            }
            else{
                return 2;
            }
        }
        $user = self::firstOrNew(
            ['open_id' => $open_id],
            ['wechat_id' => $wechat_id, 'gender' => $gender]
        );
        if ($user->save()){
            return 0;
        }
        else{
            return 3;
        }
    }
}
