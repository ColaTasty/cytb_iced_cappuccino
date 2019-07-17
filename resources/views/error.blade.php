<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-16 20:11
 */
?>
    <!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>访问错误</title>
    <meta http-equiv="content-type" content="text/html"/>
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
    <div style="margin-top: 40vh;">{{$msg}}</div>
@else
    <div style="margin-top: 40vh;">访问错误</div>
@endif
@if(isset($code))
    <div>{{$code}}</div>
@else
    <div>404</div>
@endif
<div class="copyright"><?php echo date("Y",time());?>&nbsp;&copy;&nbsp;城院贴吧&nbsp;&nbsp;</div>
</body>
</html>
