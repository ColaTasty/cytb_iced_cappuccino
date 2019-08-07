<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-07 18:19
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatQixiFeedback extends Model
{
    protected $table = "WeChatQixiFeedback";
    protected $primaryKey = "id";
    protected $fillable = ["open_id", "view_code"];
    protected $dateFormat = "Y-m-d H:i:s";

    public function feedback($open_id,$view_code){
        $res = self::firstOrCreate(
            ["open_id"=>$open_id],
            ["view_code"=>$view_code]
        );

        if (empty($res)){
            return null;
        }
        else{
            return $res;
        }
    }
}
