<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-08 01:25
 */


namespace App\CustomClasses\Utils;


class CreateRandomStr
{
    public static function CreateGuid() {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}
