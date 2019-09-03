<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-09-03 18:39
 */


namespace App;


use App\CustomClasses\Utils\WechatApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use test\Mockery\ArgumentObjectTypeHint;

class WeChatWelcomeToCy extends Model
{
    protected $table = "WeChatWelcomeToCy";
    protected $dateFormat = "Y-m-d H:i:s";
    protected $primaryKey = "id";
    protected $fillable = ['open_id', 'wechat_id', 'gender', 'screen_image'];

    /**
     * 提交信息
     * @param $open_id
     * @param $wechat_id
     * @param $gender
     * @return int
     */
    public function SubmitInfo($open_id, $wechat_id, $gender)
    {
        $user = self::where('open_id', $open_id)->first();
        if (!empty($user)) {
            if (empty($user->screen_iamge)) {
                return 1;
            } else {
                return 2;
            }
        }
        $user = new $this();
        $user->open_id = $open_id;
        $user->wechat_id = $wechat_id;
        $user->gender = $gender;
        if ($user->save()) {
            return 0;
        } else {
            return 3;
        }
    }

    /**
     * 提交图片
     * @param $open_id
     * @param $media_id
     * @return int
     */
    public function SubmitImage($open_id, $media_id)
    {
        $img = WechatApi::GetMedia(3, $media_id);
        if (empty($img)) {
            return 1;
        }

        $path_filename = 'welcome_to_cy/' . $open_id . '.jpg';
        $publicUrl = "http://makia.dgcytb.com/storage/" . $path_filename;
        if (!Storage::put('public/' . $path_filename,$img)) {
            return 2;
        }

        $user = self::where('open_id', $open_id)->first();
        if (empty($user) && empty($user->wechat_id)) {
            return 3;
        }
        $user->screen_image = $publicUrl;

        if ($user->save()) {
            return 0;
        } else {
            return 4;
        }
    }
}
