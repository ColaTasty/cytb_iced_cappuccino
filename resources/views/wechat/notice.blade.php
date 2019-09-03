<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-31 23:35
 */
?>
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>通知</title>
    <style>
        body {
            background-color: #353535;
            color: #EEEEEE;
            min-height: 100%;
        }

        .content{
            width: 100%;
            min-height: 100vh;
        }

        .footer{
            width: 100%;
            min-height: 50px;
        }

        .img-box {
            width: 80vmin;
            height: 80vmin;
            border-radius: 10px;
            margin: 0 auto;
            background-color: rgba(200, 200, 200, 0.5);
            position: relative;
        }

        .img-box img{
            display: block;
            width: 95%;
            height: 95%;
            margin: auto;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border-radius: 10px;
        }

        .txt {
            height: 10vmin;
            text-align: center;
            margin: 5vmin auto 0 auto;
            display: block;
        }

        .copyright {
            font-size: 0.8em;
            text-align: center;
            display: block;
            /*position: absolute;*/
            /*bottom: 20px;*/
            width: 100%;
            margin: 25px auto;
        }
    </style>
</head>
<body>

<h2 style="text-align: center">城院贴吧Pro</h2>
<div class="content">
    <div class="img-box">
        <img src="/image/cytbpro_qr.jpg" alt="城院贴吧Pro公众号二维码">
    </div>
    <h3 class="txt">
        {{$message}}
    </h3>
</div>
<div class="footer">
    <div class="copyright">&copy;&nbsp;{{date("Y")}}&nbsp;城院贴吧Pro</div>
</div>

</body>
</html>
