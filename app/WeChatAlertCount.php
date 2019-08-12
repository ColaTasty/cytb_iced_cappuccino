<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-07 23:19
 */


namespace App;


use App\CustomClasses\Utils\WechatApi;
use Illuminate\Database\Eloquent\Model;

class WeChatAlertCount extends Model
{
    protected $table = "WeChatAlertCount";
    protected $primaryKey = "id";
    protected $fillable = ["open_id", "count"];
    protected $dateFormat = "Y-m-d H:i:s";

    public function SendNotice($open_id)
    {
        $res = self::firstOrNew(
            ["open_id" => $open_id],
            ["count" => 4]
        );
        $res->count = ($res->count - 1);
        if ($res->save()) {
            if ($res->count <= 0) {
                WechatApi::SendTextCustomNotice($res->open_id, "*************\n你太活跃啦\n请回答我\n你是机器人🤖吗？\n*************");
            }
            return $res;
        }
        return null;
    }

    public function RefreshCount($open_id)
    {
        $res = self::updateOrCreate(
            ["open_id" => $open_id],
            ["count" => 4]
        );
        return $res;
    }
}
