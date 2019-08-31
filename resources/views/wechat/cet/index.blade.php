<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-19 22:46
 */
?>

@extends("wechat.cet.component")

@section("content")
    <p id="topTips" style="padding: 0;margin-top: 20px;margin-bottom: 0;">正在加载....</p>
    <form style="padding: 10px;height: 50vh;" id="form">
        @csrf
        <div class="form-group" style="margin-top: 30px;">
            <label for="ticket-input">准考证号</label>
            <input type="number" class="form-control" name="zkz" id="zkz-input" aria-describedby="zkzHelp"
                   placeholder="" maxlength="15">
            <small id="ticketHelp" class="form-text text-muted">输入15位有效的准考证号</small>
        </div>
        <div class="form-group">
            <label for="ticket-input">姓名</label>
            <input type="text" class="form-control" name="name" id="name-input" aria-describedby="nameHelp"
                   placeholder="">
            <small id="nametHelp" class="form-text text-muted">输入准考证号对应的姓名</small>
        </div>
        <div class="form-group">
            <label for="ticket-input">验证码</label>
            <div class="input-group">
                <input type="text" class="form-control input-lg" name="yzm" id="yzm-input" aria-describedby="yzmHelp"
                       placeholder="">
                <span class="input-group-addon" id="yzm-box"><a href="javascript:void(0)" onclick="onclick_GetYzm(this)">点击这里获取验证码</a></span>
            </div>
        </div>
        <div class="buttons" style="padding-left: 10px;padding-right: 10px;margin-top: 20px">
            <button type="button" id="sub" class="btn btn-primary btn-lg btn-block">查询</button>
        </div>
    </form>
@endsection

@section("js")
    <script>
        var _CAN_USE = false;
        var _dd = undefined;
        var topTips = '';
        // 初始化
        $.ajax({
            url: "/wxapp/cet/init",
            method: "post",
            success: function (res) {
                _CAN_USE = res.config.canUse;
                // 若是功能不可用
                if (!res.config.canUse) {
                    topTips = res.config.msg;
                    $("#sub").attr("disabled", "disabled");
                    ShowAlertModal(
                        res.config.msg + '\r\n点击【确定】跳转至官网',
                        //跳转至官网
                        function () {
                            location.href = "http://cet.neea.edu.cn/cet/";
                        }
                    );
                    return;
                }
                // 请求成功
                if (res.isOK) {
                    _dd = res.dd;
                    topTips = res.dd.subn;
                }
                // 请求失败
                else {
                    topTips = "功能不可用，请移步四六级官网查询";
                    ShowAlertModal(
                        res.msg + '\r\n点击【确定】跳转至官网查询',
                        //跳转至官网
                        function () {
                            location.href = "http://cet.neea.edu.cn/cet/";
                        }
                    );
                }
            },
            fail:function () {
                topTips = "无法连接后台服务器";
                ShowAlertModal(
                    '无法连接后台服务器，点击【确定】跳转至官网查询',
                    //跳转至官网
                    function () {
                        location.href = "http://cet.neea.edu.cn/cet/";
                    }
                );
            },
            complete:function () {
                $("#topTips").text(topTips);
            }
        });
        /**
         * 获取验证码
         * @param obj
         */
        var onclick_GetYzm = function (obj) {
            $(function () {
                var btn = $(obj);
                if (!_CAN_USE) {
                    ShowAlertModal(
                        topTips + '点击【确定】跳转至官网查询',
                        //跳转至官网
                        function () {
                            location.href = "http://cet.neea.edu.cn/cet/";
                        }
                    );
                }
            });
        }
    </script>
@endsection
