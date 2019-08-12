<?php

namespace App\Http\Middleware;

use Closure;

class QiXiActive
{
    private $start_time = "2019-08-07 20:00:00";
    private $end_time = "2019-08-09 21:30:00";
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $timestamps = strtotime($this->start_time);

        $now_time = time();

        if ($now_time < $timestamps){
            return response(view("error",["msg"=>"活动还未开始"]));
        }

        $timestamps = strtotime($this->end_time);

        if ($timestamps < $now_time){
            return response(view("error",["msg"=>"<p style='margin: 0'>本次活动已经结束</p><p style='margin: 0'>持续关注【城院贴吧Pro】公众号</p>","code"=>"获取更多城院咨询和城院活动信息😘"]));
        }

        return $next($request);
    }
}
