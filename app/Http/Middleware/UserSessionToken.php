<?php

namespace App\Http\Middleware;

use App\WeChatSessionToken;
use Closure;

class UserSessionToken
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
        if (!empty(session("open_id",null))){
            return $next($request);
        }

        if (!isset($request->active_token)){
            return response(view("error",["msg"=>"链接无效<br>请关注【城院贴吧Pro】公众号","code"=>"回复【七夕活动】重新获取链接"]));
        }

        $token = $request->active_token;

        $log = new WeChatSessionToken();

        $res = $log->VerifyToken($token);

        if (!empty($res)){
            session(["open_id"=>$res]);
            return $next($request);
        }
        else{
            return response(view("error",["msg"=>"链接失效<br>请关注【城院贴吧Pro】公众号","code"=>"回复【七夕活动】重新获取链接"]));
        }
    }
}
