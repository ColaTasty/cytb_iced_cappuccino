<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-28 17:38
 */

namespace App\Http\Controllers\WeChat;

use App\CustomClasses\Utils\HttpSendRequest;
use App\WeChatQueryExaminationMail;

class QueryExaminationMail
{
    private $header = [
        "AUTH-TOKEN: eyJhbGciOiJIUzI1NiJ9.eyJTU1NfVVJMIjoicWV4YW1nIiwiUEhPTkUiOiIiLCJVU0VSX0lEIjoiYzQ3YWRkM2U0MGJjNGYyYTg3YzJiOTNhZjI3ZTM2YzEiLCJVU0VSX0NIQU5ORUwiOiJXRUNIQVQiLCJJU1NVRVJfQVQiOjE1NjM3MTU3MzA2NDgsIk9QRU5fSUQiOiJvaWZDbWpsYmktdTVmUGlaaTd1UjhneXVVM2IwIiwiSVNTVUVSIjoibmV3LWdlbmVyYXRpb24tYXBpIiwiUEhPTkVfSUQiOiIiLCJleHAiOjE1Njg4OTk3MzB9.flkjAO1eERE1nkaHDhqP8NEg5LolG0d6KcVaVcaHs7A",
        "Content-type: application/json",
        "USER-CHANNEL: WECHAT"
    ];

    private $url = [
        "get_mail_info" => "https://ems.emspost.com.cn/new-generation-logistics/mail/logistics/subjection",
        "get_mail_num" => "https://ems.emspost.com.cn/new-generation-logistics/mail/queryMailNumByTicket"
    ];

    /**
     * @param $ticket
     * @return array|bool|mixed|string
     */
    public function query($ticket)
    {
        $res = $this->getMailNum($ticket);

        if (!$res["isOK"]){
            $res["msg"] = "这个准考证还没有物流信息";
            return $res;
        }

//        写入数据库
        $log = new WeChatQueryExaminationMail();
        $log->Insert([
            "ticket"=>$ticket,
            "mail_num"=>$res["mail_num"],
        ]);

        $res = $this->getMailInfo($res["mail_num"]);

        if (!$res["isOK"]){
            $res["msg"] = "物流信息服务器出错";
        }

//        写入数据库
        $log->UpdateDirection([
            "ticket"=>$ticket,
            "from"=>$res["mail_info"]->mail->senderCity,
            "to"=>$res["mail_info"]->mail->receiverCity
        ]);

        return $res;
    }

    /**
     * @param $ticket
     * @return array|bool|mixed|string
     */
    private function getMailNum($ticket){
        $send = new HttpSendRequest();

        $data = ["ticket"=>$ticket];
        $data = json_encode($data);

        $send->sendPost($this->url["get_mail_num"])
            ->setPostData($data)
            ->setHeader($this->header);

        $res = $send->send();
        $res = json_decode($res);

        $res = [
            "isOK" => !empty($res->info) && $res->msg=="SUCCESS",
            "mail_num" => $res->info
        ];

        return $res;
    }

    /**
     * @param $mail_num
     * @return array|bool|mixed|string
     */
    private function getMailInfo($mail_num){
        $send = new HttpSendRequest();

        $data = [
            "mailNo" => $mail_num,
            "type" => 3
        ];
        $data = json_encode($data);

        $send->sendPost($this->url["get_mail_info"])
            ->setPostData($data)
            ->setHeader($this->header);

        $res = $send->send();
        $res = json_decode($res);

        $res = [
            "isOK" => !empty($res->info) && $res->msg=="SUCCESS",
            "mail_info" => $res->info
        ];

        return $res;
    }
}
