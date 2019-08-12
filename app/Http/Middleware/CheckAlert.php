<?php

namespace App\Http\Middleware;

use App\WeChatAlertCount;
use Closure;

class CheckAlert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $open_id = session("open_id");
        $alert_count = WeChatAlertCount::where("open_id",$open_id)->first();
        if (empty($alert_count)){
            $alert_count = new WeChatAlertCount();
            $alert_count = $alert_count->RefreshCount($open_id);
        }

        if ($alert_count->count <= 0){
            return response(view("error",["msg"=>"<p style='padding: 0;margin: 0'>你太活跃啦</p>向公众号【城院贴吧Pro】重新获取活动入口","code"=>"以此证明你不是机器人"]));
        }

        return $next($request);
    }
}
