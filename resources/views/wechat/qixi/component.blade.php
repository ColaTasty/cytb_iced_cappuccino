<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-29 21:23
 */
?>
    <!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @section('title')
            城院贴吧-七夕活动
        @show
    </title>
    <link href="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/weui/2.0.1/style/weui.css" rel="stylesheet">
    <link href="https://makia.dgcytb.com/css/wechat/qixi/global.css" rel="stylesheet">
    @section('css')
    @show
</head>

<body>
{{--导航栏--}}
<nav class="navbar navbar-default cytb-nav">
    <div class="container-fluid">
        <div class="navbar-header">
            <img class="cytb-nav-logo" alt="Brand" src="https://makia.dgcytb.com/image/tieba_logo.png">
            <span class="text-center" style="width: 100%;position: absolute;left: 0;right: 0;top: 8px;">七夕特别活动</span>
        </div>
    </div>
</nav>
{{--正在加载--}}
<div id="loading" class="loading">
    <img class="box" src="https://makia.dgcytb.com/image/Ellipsis-2s-200px.gif"/>
</div>
@section('content')
    <h2 style="width:100%;text-align:center">网页出错了T^T&nbsp;|&nbsp;404&nbsp;Not&nbsp;Found</h2>
@show
<!-- 底部 -->
<div class="cytb-footer cytb-bg-black">
    <span>&copy;&nbsp;{{date("Y")}}&nbsp;城院贴吧Pro</span>
</div>
<!-- 底部end -->
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
<script src="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdn.bootcss.com/vue/2.6.10/vue.min.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
@section('js')
@show
<script>
    $(function () {
        $("#loading").fadeOut();
    })
</script>
</body>

</html>
