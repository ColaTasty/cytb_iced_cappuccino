<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeChatAdmin extends Model
{
    protected $dateFormat = "Y-m-d H:i:s";
    protected $table = "WeChatAdmin";
    protected $primaryKey = "id";
    protected $fillable = ["open_id", "level", "expire", "operator_open_id"];

    public function AddAdmin($open_id, $level, $operator_open_id)
    {
        $now = time();

//        如果是超级open_id，跳过验证
        if ($operator_open_id == "8520123") {
        } else {
            $operator_admin = self::where("open_id", $operator_open_id)->first();
//            操作者不是管理员
            if (empty($operator_admin)) {
                return 1;
            }
//            操作者管理员期限失效
            $expire = strtotime($operator_admin->expire);
            if ($expire < $now) {
                return 2;
            }
//            操作者权限不够
            if ($operator_admin->level < $level) {
                return 3;
            }
        }

        $admin = self::updateOrCreate(
            ["open_id" => $open_id],
            ["level" => $level, "expire" => date("Y-m-d H:i:s", $now + 60 * 60 * 24 * 30), "operator_open_id" => $operator_open_id]
        );
//            未知原因保存失败
        if (empty($admin)) {
            return 4;
        } else {
            return $admin;
        }
    }
}
