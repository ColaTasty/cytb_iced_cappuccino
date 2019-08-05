<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-05 10:48
 */


namespace App;


use App\CustomClasses\Utils\WechatApi;
use Illuminate\Database\Eloquent\Model;

class WeChatJsApi extends Model
{
    protected $table = "WeChatQixiUser";
    public $timestamps = false;
    protected $primaryKey = "open_id";
    protected $fillable = ["account_id","ticket","expire"];

    public function GetTicket($account_id){
        $now = time();
        $ticket = self::find($account_id);

        if (empty($ticket)){
            $ticket = $this->RefreshTicket($account_id);
            if (empty($ticket)){
                return false;
            }

            return $ticket->ticket;
        }

        $expire = strtotime($ticket->expire);

        if ($expire < $now){
            $ticket = $this->RefreshTicket($account_id);
            if (empty($ticket)){
                return false;
            }
        }
        return $ticket->ticket;
    }

    private function RefreshTicket($account_id){
        $now = time();

        $ticket = WechatApi::GetJsApi($account_id);
        if (empty($ticket)){
            return null;
        }
        $ticket = self::updateOrCreate(
            ["account_id"],
            ["ticket"=>$ticket,"expire"=>date("Y-m-d H:i:s",$now+7200)]
        );

        return $ticket;
    }
}
