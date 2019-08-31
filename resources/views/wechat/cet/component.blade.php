<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-19 22:46
 */
?>

    <!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>城院贴吧-四六级查询</title>
    <link rel="stylesheet" href="https://makia.dgcytb.com/css/wechat/cet/component.css">
    <link href="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/weui/2.0.1/style/weui.css" rel="stylesheet">
    @section("css")
    @show
</head>
<body>
<!--#region    模态弹窗 通知    -->
<div class="modal" id="alert-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">通知</h4>
            </div>
            <div class="modal-body">
                <p id="modal-alert-msg"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="modal-alert-confirm"
                        onclick="onclick_AlertConfirm()" data-dismiss="modal">确定
                </button>
                <button type="button" class="btn btn-default" id="modal-alert-cancel" onclick="onclick_AlertCancel()"
                        data-dismiss="modal">关闭
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--#endregion    模态弹窗 通知 end    -->
<!-- header -->
<div class="cytb-header">
    <img id="logo" src="https://makia.dgcytb.com/image/tieba_logo.png">
    <p class="active">四六级查询</p>
</div>
<!-- header end -->
<!-- content -->
<div class="cytb-content">
    @section("content")
        <h2 style="width:100%;text-align:center">网页出错了T^T&nbsp;|&nbsp;5xx&nbsp;Some&nbsp;Error...</h2>
    @show
</div>
<!-- content end -->
<!-- footer -->
<div class="cytb-footer">
    <span>&copy;&nbsp;{{date("Y")}}&nbsp;城院贴吧Pro</span>
</div>
<!-- footer end -->
<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
<script src="https://cdn.bootcss.com/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script>/**
     *
     * @param msg
     * @param confirm
     * @param cancel
     * @constructor
     */
    var ShowAlertModal = function (msg,
                                   confirm = function () {
                                   },
                                   cancel = function () {
                                   }) {
            $("#modal-alert-msg").text(msg);
            $("#alert-modal").modal("show");
            onclick_AlertConfirm = confirm;
            onclick_AlertCancel = cancel;
        };
    /**
     *
     */
    var onclick_AlertConfirm = function () {

    };
    /**
     *
     */
    var onclick_AlertCancel = function () {

    };
</script>
@section("js")
@show
</body>
</html>
