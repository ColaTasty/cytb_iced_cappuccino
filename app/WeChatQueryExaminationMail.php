<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-28 23:57
 */


namespace App;


use App\CustomClasses\Utils\HttpSendRequest;
use Illuminate\Database\Eloquent\Model;

class WeChatQueryExaminationMail extends Model
{
    public $timestamps = false;
    protected $table = "WeChatQueryExaminationMail";
    protected $primaryKey = "id";
    protected $fillable = ["ticket","mail_num","from","to","updated_at"];

    /**
     * @param $info
     * @return mixed
     */
    public function Insert($info){
        $ticket = $info["ticket"];
        $mail_num = $info["mail_num"];
        $from = $info["from"];
        $to = $info["to"];
        $updated_at = date("Y-m-d H:i:s");

        $res =  $this->updateOrCreate(
            ["ticket"=>$ticket],
            ["mail_num"=>$mail_num,"from"=>$from,"to"=>$to,"updated_at"=>$updated_at]
        );

        return $res;
    }

    public function UpdateDirectionIfNull(){
        foreach (self::where("from",null)->cursor() as $info){
            $send = new HttpSendRequest();
            $url = "https://makia.dgcytb.com/wechat/query-examination-mail/";
            $url .= $info->ticket;

            $res = $send->sendGet($url)->send();
            if (empty($res)){
                return false;
            }
        }

        return true;
    }
}
