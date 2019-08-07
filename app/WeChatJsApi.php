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
    protected $table = "WeChatJsApi";
    public $timestamps = false;
    protected $primaryKey = "account_id";
    protected $fillable = ["account_id", "ticket", "expire"];

    public function GetTicket($account_id)
    {
        $now = time();
        $ticket = self::find($account_id);

        if (empty($ticket)) {
            $ticket = $this->RefreshTicket($account_id);
            if (empty($ticket)) {
                return null;
            }

            return $ticket->ticket;
        }

        $expire = strtotime($ticket->expire);

        if ($expire < $now) {
            $ticket = $this->RefreshTicket($account_id);
            if (empty($ticket)) {
                return null;
            }
        }
        return $ticket->ticket;
    }

    private function RefreshTicket($account_id)
    {
        $now = time();

        $ticket = WechatApi::GetJsApi($account_id);
        if (empty($ticket)) {
            return null;
        }
        $ticket = self::updateOrCreate(
            ["account_id" => $account_id],
            ["ticket" => $ticket, "expire" => date("Y-m-d H:i:s", $now + 7200)]
        );

        return $ticket;
    }

    public function GetJsConfig($account_id, $url, $api_list = null, $debug = false)
    {
        $nonce = md5(random_int(0, 10) . time() . "cytb");

        $ticket = $this->GetTicket($account_id);
        if (empty($ticket)) {
            return null;
        }

        $timestamp = time();

        $str1 = "jsapi_ticket=$ticket&noncestr=$nonce&timestamp=$timestamp&url=$url";

        $signature = sha1($str1);

        $app_id = WeChatAccount::find(3);
        $app_id = $app_id->appId;

        $api_list = empty($api_list) ? [
            "chooseImage", "previewImage", "uploadImage", "downloadImage"
        ] : $api_list;

        $js_config = [
            "debug" => $debug,
            "appId" => $app_id,
            "timestamp" => $timestamp,
            "nonceStr" => $nonce,
            "signature" => $signature,
            "jsApiList" => $api_list,
            "url"=>$url
        ];

        return $js_config;
    }
}
