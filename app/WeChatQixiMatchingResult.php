<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-05 22:56
 */


namespace App;


use App\CustomClasses\Utils\CreateRandomStr;
use Illuminate\Database\Eloquent\Model;

class WeChatQixiMatchingResult extends Model
{
    protected $table = "WeChatQixiMatchingResult";
    protected $primaryKey = "id";
    protected $fillable = ["open_id", "other_open_id", "status", "view_code"];
    protected $dateFormat = "Y-m-d H:i:s";

    public function Matching($open_id)
    {
        $user = WeChatQixiUser::where("open_id", $open_id)->first();
        if ($user->gender == 0) {
            $others = WeChatQixiUser::where("gender", 1)->where("status",1);
        } else {
            $others = WeChatQixiUser::where("gender", 0)->where("status",1);
        }

        $others_count = $others->count();
        if ($others_count == 0) {
            return null;
        }
        $others = $others->get()->toArray();

        $random = random_int(0, ($others_count - 1));

        $other = $others[$random];

        $view_code = CreateRandomStr::CreateGuid();
        $result = WeChatQixiMatchingResult::firstOrCreate(
            ["open_id" => $open_id, "other_open_id" => $other["open_id"]],
            ["view_code" => $view_code]
        );

        if (empty($result)) {
            return null;
        } else {
            return $result;
        }
    }

    public function WantMatching($open_id, $other_open_id)
    {
        $now = time();
        $view_code = md5($now . $open_id);

        $other_result = WeChatQixiMatchingResult::firstOrCreate(
            ["open_id" => $other_open_id, "other_open_id" => $open_id],
            ["view_code" => $view_code]
        );

        if (empty($other_result)) {
            return null;
        } else {
            return $other_result;
        }
    }
}
