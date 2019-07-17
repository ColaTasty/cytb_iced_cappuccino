<?php

use \App\CustomClasses\Utils\ResponseConstructor as RC;

$feature_list = [[
    "title" => "四六级查询",
    "name" => "CET4/6",
    "url" => "./../cet/cet",
    "bgColor" => "bg-yellow",
    "icon" => "search"
], [
    "title" => "我的课表",
    "name" => "schedule",
    "url" => "./../index/index",
    "bgColor" => "bg-yellow",
    "icon" => "calendar"
], [
    "title" => "吃什么",
    "name" => "lunch/dinner",
    "url" => "./../index/index",
    "bgColor" => "bg-yellow",
    "icon" => "shopfill"
], [
    "title" => "畅谈广场",
    "name" => "Let's talk",
    "url" => "./../index/index",
    "bgColor" => "bg-yellow",
    "icon" => "communityfill"
], [
    "title" => "投票器",
    "name" => "Vote",
    "url" => "./../index/index",
    "bgColor" => "bg-yellow",
    "icon" => "post"
]];
RC::SetStatus(true);
RC::SetMsg("获取成功");
RC::SetData("list", $feature_list);
RC::SetData("expire", (time() + 7200) * 1000);
echo json_encode(RC::GetResponse());
