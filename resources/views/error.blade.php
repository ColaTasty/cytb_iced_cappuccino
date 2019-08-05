<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-16 20:11
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>访问错误</title>
    <style>
        div {
            text-align: center;
            font-size: 24px;
        }
        .copyright{
            width: 100%;
            position: absolute;
            bottom: 50px;
            font-size: 15px;
        }
    </style>
</head>
<body>
@if(isset($msg))
    <div style="margin-top: 40vh;"><?php echo $msg;?></div>
@else
    <div style="margin-top: 40vh;">访问错误</div>
@endif
@if(isset($code))
    <div>{{$code}}</div>
@else
    <div>404</div>
@endif
<div class="copyright">&copy;&nbsp;<?php echo date("Y",time());?>&nbsp;城院贴吧&nbsp;&nbsp;</div>
</body>
</html>
