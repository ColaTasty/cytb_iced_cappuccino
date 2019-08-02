<?php

namespace App\Http\Middleware;

use Closure;

class QiXiActive
{
    private $start_time = "2019-08-07 23:59:59";
    private $end_time = "2019-08-07 00:00:00";
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
            return response(view("wechat.qixi.msg",["msg"=>"活动还未开始"]));
        }

        $timestamps = strtotime($this->end_time);

        if ($timestamps < $now_time){
            return response(view("wechat.qixi.msg",["msg"=>"活动已经结束"]));
        }

        return $next($request);
    }
}
