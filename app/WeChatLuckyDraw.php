<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-01 16:32
 */


namespace App;


use Illuminate\Database\Eloquent\Model;

class WeChatLuckyDraw extends Model
{
    protected $dateFormat = "Y-m-d H:i:s";
    protected $table = "WeChatLuckyDraw";
    protected $fillable = ["draw_name","limit_people","start_time","end_time"];

    /**
     * @param $info
     * @return mixed
     */
    public function InsertDraw($info){
        $draw_name = $info["draw_name"];

        $limit_people = $info["limit_people"];

        $start_time = $info["start_time"];

        $end_time = $info["end_time"];

        $res = self::updateOrCreate(
            ["draw_name" => $draw_name],
            ["limit_people"=>$limit_people,"start_time"=>$start_time,"end_time"=>$end_time]
        );

        return $res;
    }
}
