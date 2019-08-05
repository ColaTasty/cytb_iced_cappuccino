<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-05 02:31
 */


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WeChatQixiUser extends Model
{
    protected $table = "WeChatQixiUser";
    protected $primaryKey = "open_id";
    protected $fillable = ["open_id", "name", "gender", "description", "image", "status", "msg_code"];
    protected $dateFormat = "Y-m-d H:i:s";

    private $image_root = __DIR__ . "/../public/storage/qixi";

    public function HaveInfo($open_id)
    {
        $now = time();

        $msg_code = substr(md5($open_id . $now), 0, 10);

        $log = WeChatQixiUser::firstOrCreate(
            ["open_id" => $open_id],
            ["msg_code" => $msg_code]
        );

        if (empty($log->name))
            return false;
        else
            return true;
    }

    public function IsMatching($open_id)
    {
        $now = time();

        $msg_code = substr(md5($open_id . $now), 0, 10);

        $log = WeChatQixiUser::firstOrCreate(
            ["open_id" => $open_id],
            ["msg_code" => $msg_code]
        );

        if ($log->status == 0)
            return false;
        else
            return true;
    }

    public function Insert($open_id, $name, $gender, $description)
    {
        $user = WeChatQixiUser::find(
            $open_id
        )->update([
            "name" => $name,
            "gender" => $gender,
            "description" => $description
        ]);

        if (empty($user)){
            return false;
        }
        else{
            return true;
        }
    }

    public function SaveImage($open_id, $image)
    {
        $image_url = [];

        foreach ($image as $file) {
//            检查文件是否上传成功
            if ($file->isValid()) {
//                检查扩展名
                $ext = $file->extension();
                switch ($ext) {
                    default:
                        return 2;
                    case "jpeg":
                    case "jpg":
                    case "png":
                        break;
                }
//                开始存储
                $fileName = time() + random_int(0, 10) . "." . $ext;

                $publicUrl = "http://makia.dgcytb.com/storage/" . $open_id . "/" . $fileName;
                array_push($image_url, $publicUrl);

                if (!$file->storeAs("public/qixi/".$open_id, $fileName)) {
                    return 3;
                };
            } else {
                return 1;
            }
        }

        $detail = [
            "image_url" => $image_url
        ];
        $detail = json_encode($detail);

        $user = self::find($open_id);
        $user->image = $detail;

        if ($user->save()) {
            return true;
        } else {
            return 5;
        }
    }
}
