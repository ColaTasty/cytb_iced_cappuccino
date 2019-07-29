<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-28 17:41
 */

?>
    <!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>城院贴吧——通知书物流</title>
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://makia.dgcytb.com/css/cytb_header.css">
</head>
<body>
@if(!$res["isOK"])
    <script>
        alert("{{$res["msg"]}}");
        window.history.back(-1);
    </script>
@else
    <input class="cytb-tmp-input" type="text" id="tmp-input">
    <div class="header">
        <img id="logo" src="https://makia.dgcytb.com/image/tieba_logo.png">
        <p class="active">通知书物流</p>
    </div>
    <?php $mail = $res["mail_info"]->mail;?>
    <div class="cytb-direction">
        <p style="margin: 0;padding: 0;"><span class="direction"><?php echo $mail->senderCity; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                class="direction"><?php echo $mail->receiverCity; ?></span></p>
        <p style="margin: 0;padding: 0;font-size: 14px">
            共耗时<?php echo $mail->lastMailInfo[count($mail->lastMailInfo) - 1]->dateDiffStr; ?></p>
    </div>
    <div class="info">
        &nbsp;&nbsp;&nbsp;物流单号:<span id="mail-num"><?php echo $mail->mailNo; ?></span>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="onclick_CopyMailNum()">复制</a><br><br>
        <table class="table" style="text-align: center;">
            <thead>
            <tr>
                <th scope="col" style="width: 5%;">序号</th>
                <th scope="col" style="width: 8%;">时间</th>
                <th scope="col">信息</th>
                <th scope="col" style="width: 10%;">状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $infoArr = $mail->mailInfos;
            $len = count($infoArr);
            for($i = $len - 1;$i >= 0;$i--){
            $info = $infoArr[$i];
            $timestamps = strtotime($info->time);
            $date = date("Y-m-d",$timestamps);
            $date_timestamps = strtotime($date);
            $today_timestamps = strtotime(date("Y-m-d"));
            $time_diff = $today_timestamps - $date_timestamps;
            switch ($time_diff){
                case 0:
                    $date = "今天";
                    break;
                case 3600*24:
                    $date = "昨天";
                    break;
                case 3600*24*2:
                    $date = "前天";
                    break;
                default:
                    break;
            }
            ?>
            <tr class="cytb-tr">
                <th scope="row"><?php echo $i+1 ?></th>
                <td style="font-size: 9px"><?php echo $date; ?><br><?php echo date("H:i:s",$timestamps); ?></td>
                <td style="font-size: 13px"><?php echo $info->operation; ?></td>
                <td style="font-size: 9px"><?php echo $info->stateStr; ?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="cytb-copyright">
        &copy;&nbsp;<?php echo date("Y");?>&nbsp;城院贴吧
    </div>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://makia.dgcytb.com/js/wechat/query_examination_mail.js"></script>
@endif
</body>
</html>
