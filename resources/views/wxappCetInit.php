<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-16 23:45
 */

use \App\CustomClasses\Utils\ResponseConstructor as RC;

$config = [
    "canUse" => true,
    "msg" => "服务器大姨妈啦！\n请到官网查询!!"
];

RC::SetData("config", $config);
echo json_encode(RC::GetResponse());
