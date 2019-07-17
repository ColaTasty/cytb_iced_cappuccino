<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-17 15:38
 */


namespace App\CustomClasses\Utils;


class CetApi
{
    private static $urls = [
        "referer" => "http://cet.neea.edu.cn/cet/",
        "verify" => "http://cache.neea.edu.cn/Imgs.do",
        "query" => "http://cache.neea.edu.cn/cet/query"
    ];

    public static function CetGetVerifyImage($zkz)
    {
        $data = [
            "c" => "CET",
            "ik" => $zkz,
            "t" => random_int(1, 10)
        ];

        $header = [
            "Referer:" . self::$urls["referer"]
        ];

        $send = new HttpSendRequest();
        $send->sendGet(self::$urls["verify"] . "?" . HttpSendRequest::dataArrayToString($data))
            ->needHeader(true)
            ->setHeader($header);

        $res = $send->send();

        if ($send->curl_info["http_code"] != 200) {
            return false;
        }
        // 解析http数据流
        list($header, $body) = explode("\r\n\r\n", $res);

        // 解析cookie
        preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches);
        $cookie = $matches[1];
        $cookie = str_replace(" ", "", $cookie);

        // 解析图片地址
        preg_match("/result\.imgs\(\"(.*)\"\);/i", $body, $img_url);
        $img_url = $img_url[1];

        $callback = [
            "url" => $img_url,
            "cookie" => $cookie
        ];

        return $callback;
    }

    public static function Query($zkz, $name, $v, $t, $cookie)
    {
        $data = [
            "data" => "$t,$zkz,$name",
            "v" => $v
        ];

        $str_data = "data={$data["data"]}&v={$data["v"]}";

        $header = [
            "Referer:".self::$urls["referer"]
        ];

        $send = new HttpSendRequest();
        $send->sendPost(self::$urls["query"])
//            ->needHeader(true)
            ->setHeader($header)
            ->setPostData($str_data)
            ->setCookie($cookie);

        $res = $send->send();

        if ($send->curl_info["http_code"] != 200){
            return false;
        }

        // 解析结果
        preg_match("/script>parent\.result\.callback\(\"{(.*)}\"\);<\/script>/i", $res, $res_body);
        $need = $res_body[1];
        $need = str_replace(",",";",$need);
        $need = str_replace(":","=",$need);
        $need = str_replace("'","",$need);
        $need = explode(";",$need);
        $callback = [];
        foreach ($need as $item){
            $a = explode("=",$item);
            $callback[$a[0]] = $a[1];
        }

        return $callback;
    }
}
