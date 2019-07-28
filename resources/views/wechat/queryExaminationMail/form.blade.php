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
    <title>城院贴吧——查询通知书物流</title>
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://makia.dgcytb.com/css/cytb_header.css">
</head>
<body>
<div class="header">
    <img id="logo" src="https://makia.dgcytb.com/image/tieba_logo.png">
    <p class="active">查询通知书物流</p>
</div>
<form action="/wechat/query-examination-mail/" style="padding: 10px;height: 50vh;" method="post" id="form">
    @csrf
    <div class="form-group" style="margin-top: 30px;">
        <label for="ticket-input">准考证号</label>
        <input type="number" class="form-control" name="ticket" id="ticket-input" aria-describedby="ticketHelp" placeholder="">
        <small id="ticketHelp" class="form-text text-muted">输入有效的准考证号</small>
        <div class="buttons" style="padding-left: 10px;padding-right: 10px;margin-top: 20px">
            <button type="button" id="sub" class="btn btn-primary btn-lg btn-block">查询</button>
        </div>
    </div>
</form>
<div class="copyright">
    &copy;&nbsp;<?php echo date("Y");?>&nbsp;城院贴吧
</div>
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://makia.dgcytb.com/js/wechat/query_examination_mail.js"></script>
</body>
</html>
