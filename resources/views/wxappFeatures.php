<?php

use \App\CustomClasses\Utils\ResponseConstructor as RC;

$feature_list = [[
    "title" => "四六级查询",
    "name" => "CET4/6",
    "url" => "./../cet/cet",
    "bgColor" => "bg-gradual-blue",
    "icon" => "search"
], [
    "title" => "我的课表",
    "name" => "schedule",
    "url" => "./../index/index",
    "bgColor" => "bg-gradual-red",
    "icon" => "calendar"
], [
    "title" => "吃什么",
    "name" => "lunch/dinner",
    "url" => "./../index/index",
    "bgColor" => "bg-gradual-green",
    "icon" => "shopfill"
], [
    "title" => "畅谈广场",
    "name" => "Let's talk",
    "url" => "./../index/index",
    "bgColor" => "bg-gradual-pink",
    "icon" => "communityfill"
], [
    "title" => "投票器",
    "name" => "Vote",
    "url" => "./../index/index",
    "bgColor" => "bg-gradual-purple",
    "icon" => "post"
]];
RC::SetStatus(true);
RC::SetMsg("获取成功");
RC::SetData("list",$feature_list);
echo json_encode(RC::GetResponse());
