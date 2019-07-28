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
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://makia.dgcytb.com/css/cytb_header.css">
</head>
<body>
@if(!$res["isOK"])
    <script>
        alert({{$res["msg"]}});
        window.history.back(-1);
    </script>
@else
    <div class="header">
        <img id="logo" src="https://makia.dgcytb.com/image/tieba_logo.png">
        <p class="active">通知书物流</p>
    </div>
    <?php $mail = $res["mail_info"]->mail;?>
    <div class="cytb-direction">
        <p style="margin: 0;padding: 0;"><span class="direction"><?php echo $mail->senderCity; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                class="direction"><?php echo $mail->receiverCity; ?></span></p>
        <p style="margin: 0;padding: 0;font-size: 14px">
            耗时<?php echo $mail->lastMailInfo[count($mail->lastMailInfo) - 1]->dateDiffStr; ?></p>
    </div>
    <div class="info">
        <table class="table" style="text-align: center;">
            <thead>
            <tr>
                <th scope="col" style="width: 20%;">时间</th>
                <th scope="col">信息</th>
                <th scope="col" style="width: 20%;">状态</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $infoArr = $mail->mailInfos;
            $len = count($infoArr);
            for($i = $len - 1;$i > 0;$i--){
            $info = $infoArr[$i];
            $timestamps = strtotime($info->time);
            ?>
            <tr>
                <td style="font-size: 9px"><?php echo date("Y-m-d",$timestamps); ?><br><br><?php echo date("H:i:s",$timestamps); ?></td>
                <td><?php echo $info->operation; ?></td>
                <td style="font-size: 14px"><?php echo $info->stateStr; ?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="copyright">
        &copy;&nbsp;<?php echo date("Y");?>&nbsp;城院贴吧
    </div>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
    <script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.js"></script>
    <script src="https://makia.dgcytb.com/js/wechat/query_examination_mail.js"></script>
@endif
</body>
</html>
