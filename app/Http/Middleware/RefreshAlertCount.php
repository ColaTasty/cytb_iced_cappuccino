<?php

namespace App\Http\Middleware;

use App\CustomClasses\Utils\ResponseConstructor;
use App\WeChatAlertCount;
use Closure;

class RefreshAlertCount
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
        $open_id = session("open_id",null);
        if (!isset($request->open_id) && empty($open_id)){
            ResponseConstructor::SetStatusAndMsg(false,"openId缺失 : OpenId not found");
            return ResponseConstructor::ResponseToClient(true);
        }
        $open_id = $request->open_id;
        $alert_count = new WeChatAlertCount();
        $alert_count = $alert_count->RefreshCount($open_id);
        if (empty($alert_count)){
            ResponseConstructor::SetStatusAndMsg(false,"刷新失败 : Refresh failed");
            return ResponseConstructor::ResponseToClient(true);
        }

        return $next($request);
    }
}
