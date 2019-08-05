<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 15:19
 */
?>

@extends("wechat.qixi.component")

@section("title")
    城院贴吧——七夕活动
@endsection

@section("css")
    <style>
        .cytb-rule {
            background-color: rgba(0, 0, 0, 0.5);
            margin: 10px;
            padding: 5px 5px 10px 25px;
            color: #FFFFFF;
            border-radius: 10px;
            min-height: 50px;
        }

        .cytb-rule ol,
        .cytb-rule ul {
            padding: 0;
        }

        .cytb-rule .title {
            font-weight: bold;
        }

        #tmp-input {
            position: fixed;
            top: -1000px;
        }

        .cytb-btn-group {
            width: 100%;
            min-height: 50px;
        }

        .cytb-btn-group.cytb-column {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cytb-btn-group.cytb-column button {
            margin: 0 auto;
            margin-top: 5px;
        }

        #modal-show-img {
            width: 100%;
            margin: 0 auto;
        }

        .modal-body .img-box {
             width: 90%;
             margin: 0 auto;
         }

        .cytb-upload-images{
            list-style: none;
            width: auto;
        }

        .cytb-upload-images li{
            float: left;
            margin: 5px;
            border: 1px solid #999999;
            border-radius: 3px;
            overflow: hidden;
            height: 48px;
        }

        .cytb-upload-images li img{
            width: 48px;
        }

        .cytb-upload-inputs{
            position: relative;
            width: 48px;
            height: 48px;
            margin: 5px;
            box-sizing: border-box;
            background-color: #ededed;
        }
    </style>
@endsection

@section("content")
    <input type="text" id="tmp-input"/>
    <div class="cytb-rule">
        <h4 class="title text-center">匹配规则</h4>
        <ol>
            <li>首先填写并提交匹配信息，填写之后不能修改，<strong>如被骚扰，请向公众号发送【七夕反馈】，审核通过后即可修改信息</strong></li>
            <li>提交匹配信息后，系统会分发一个【通行码】，请在公众号回复【七夕匹配 通信码】，系统才会将用户加入匹配队列<br>例如【通信码】为123，则应该在公众号回复：七夕匹配 123</li>
            <li>每小时的25分和55分左右，公众号会放出匹配结果，请注意查看</li>
            <li>被匹配到的双方，必须都同意公开信息之后，才能互看匹配信息</li>
            <li>匹配时间超过2小时没有做出决定的匹配，将视为拒绝公开信息</li>
            <li><strong>请勿提交任何有害信息，一经发现严惩不贷！</strong></li>
        </ol>
    </div>
    <?php $matching = isset($matching) ? $matching : false; ?>
    @if($matching)
        <button type="button" class="btn btn-danger" id="cancel-matching">取消匹配</button>
    @else
        {{--    模态弹窗 提交信息    --}}
        <div class="modal fade" id="submit-info" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">提交信息</h4>
                    </div>
                    <div class="modal-body" style="height: auto">
                        <!-- 表单 -->
                        <form id="form-user-info" action="/wechat/qixi/submit-info" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">称呼</label><br>
                                <input id="name" class="form-control" name="name" type="text" placeholder="不超过15字" maxlength="15" style="width: 100%;">
                            </div>
                            <div class="form-group">
                                <label for="gender">性别</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="-1" selected>请选择</option>
                                    <option value="1">男</option>
                                    <option value="0">女</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">自我介绍</label><br>
                                <textarea name="description" class="form-control" id="description" placeholder="不超过300字" maxlength="300" style="width: 100%" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="pictures">上传至少一张照片（最多三张）</label>
                                <div id="images-box" style="min-height: 96px;">
                                    <ul class="cytb-upload-images" id="images"></ul>
                                    <div class="weui-uploader__input-box" id="uploader-box">
                                        <input id="uploader-input" class="weui-uploader__input" type="file" accept="image/*" multiple />
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- 表单 end -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submit" class="btn btn-success">提交</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        {{--    模态弹窗 图片    --}}
        <div class="modal fade" id="img-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="img-box">
                            <img id="modal-show-img" src="" alt="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="delete-image">删除</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="cytb-btn-group cytb-column">
            <button type="button" class="btn btn-success btn-lg" style="width: 50%" id="start-matching">开始匹配</button>
        </div>
    @endif
@endsection

@section("js")
    <script src="https://makia.dgcytb.com/js/wechat/qixi/submit_images.js"></script>
    <script>
        @if($matching)
        $("#cancel-matching").on("click", function () {
            if (confirm("确定取消匹配吗？")) {
                $.ajax({
                    url: "/wechat/qixi/cancel-matching",
                    dataType: "json",
                    success: function (res) {
                        if (res.isOK) {
                            alert("取消成功，如果想要再进行匹配的话，向公众号回复新的【通信码】即可");
                        } else {
                            alert(res.msg);
                        }
                    }
                });
            }
        });
        @else
        $("#start-matching").on("click", function () {
            // $("#submit-info").modal("show");
            // return;
            $.ajax({
                url: "/wechat/qixi/start-matching",
                dataType: "json",
                success: function (res) {
                    //已提交信息
                    if (res.isOK) {
                        _copy_msgCode(res.msgCode);
                        alert("去公众号按规则回复通信码\n通信码【" + res.msgCode + "】\n已经粘贴到你的剪贴板");
                    }
                    //未提交信息
                    else {
                        alert("你还未提交匹配信息");
                        $("#submit-info").modal("show");
                    }
                }
            });
        });
        @endif
    </script>
@endsection
