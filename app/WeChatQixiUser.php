<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-05 02:31
 */


namespace App;


use App\CustomClasses\Utils\WechatApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WeChatQixiUser extends Model
{
    protected $table = "WeChatQixiUser";
    protected $primaryKey = "id";
    protected $fillable = ["open_id", "name", "contact", "gender", "description", "image", "status"];
    protected $dateFormat = "Y-m-d H:i:s";

    private $image_root = __DIR__ . "/../public/storage/qixi";

    public function Insert($open_id, $name, $contact, $gender, $description)
    {
        $user = WeChatQixiUser::updateOrCreate(
            ["open_id" => $open_id],
            [
                "name" => $name,
                "contact" => $contact,
                "gender" => $gender,
                "description" => $description
            ]);

        if (empty($user)) {
            return null;
        } else {
            return $user;
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

                $publicUrl = "http://makia.dgcytb.com/storage/qixi/" . $open_id . "/" . $fileName;
                array_push($image_url, $publicUrl);

                if (!$file->storeAs("public/qixi/" . $open_id, $fileName)) {
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

        $user = self::firstOrCreate(
            ["open_id"=>$open_id]
        );
        $user->image = $detail;

        if ($user->save()) {
            return true;
        } else {
            return 5;
        }
    }

    public function SaveImageFromWeChat($open_id, $image)
    {
        $image_url = [];

        foreach ($image as $media_id) {
//                开始存储
            $file = WechatApi::GetMedia(3, $media_id);
            $fileName = time() + random_int(0, 10) . ".jpg";
            $publicUrl = "http://makia.dgcytb.com/storage/qixi/" . $open_id . "/" . $fileName;
            array_push($image_url, $publicUrl);

            if (!Storage::put("public/qixi/" . $open_id . "/" . $fileName, $file)) {
                return 3;
            };
        }

        $detail = [
            "image_url" => $image_url
        ];
        $detail = json_encode($detail);

        $user = self::firstOrCreate(
            ["open_id"=>$open_id]
        );
        $user->image = $detail;

        if ($user->save()) {
            return true;
        } else {
            return 5;
        }
    }
}
