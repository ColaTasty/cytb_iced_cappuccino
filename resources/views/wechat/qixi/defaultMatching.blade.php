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
        .cytb-carousel {
            width: 100%;
            height: 300px;
            overflow: hidden;
            box-sizing: border-box;
        }

        .cytb-carousel .img-box {
            height: 300px;
        }

        .cytb-carousel .img-box img {
            display: block;
            height: 100%;
            margin: 0 auto;
        }

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

        .cytb-upload-images {
            list-style: none;
            width: auto;
        }

        .cytb-upload-images li {
            float: left;
            margin: 5px;
            border: 1px solid #999999;
            border-radius: 3px;
            overflow: hidden;
            height: 48px;
        }

        .cytb-upload-images li img {
            width: 48px;
        }

        .cytb-upload-inputs {
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
                    <p id="modal-alert-content"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--#endregion    模态弹窗 通知 end    -->
    <!--#region    模态弹窗 提交信息    -->
    <div class="modal" id="submit-info" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">提交信息</h4>
                </div>
                <div class="modal-body" style="height: auto">
                    <!-- 表单 -->
                    <form id="form-user-info" action="/wechat/qixi/submit-info" method="post"
                          enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">称呼</label><br>
                            <input id="name" class="form-control" name="name" type="text" placeholder="不超过15字"
                                   maxlength="15" style="width: 100%;">
                        </div>
                        <div class="form-group">
                            <label for="contact">联系方式</label><br>
                            <input id="contact" class="form-control" name="contact" type="text" placeholder="不超过30字"
                                   maxlength="30" style="width: 100%;">
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
                            <textarea name="description" class="form-control" id="description"
                                      placeholder="不超过300字"
                                      maxlength="300" style="width: 100%" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="pictures">上传至少一张照片（最多三张）</label>
                            <div id="images-box" style="min-height: 96px;">
                                <ul class="cytb-upload-images" id="images"></ul>
                                <div class="weui-uploader__input-box" id="uploader-box"></div>
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
    <!--#endregion -->
    <!--#region    模态弹窗 图片    -->
    <div class="modal" id="img-modal" tabindex="-1" role="dialog">
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
    <!--#endregion-->
    <!--#region 主体 -->
    <div class="cytb-content">
        <!--    如果不在匹配    -->
        <div class="cytb-rule">
            <h4 class="title text-center">匹配规则</h4>
            <ol>
                <li><strong>请注意,提交后的信息不能被修改</strong></li>
                <li>被匹配到的双方，必须都同意公开信息之后，才能互看匹配信息</li>
                <li>请保持关注公众号【城院贴吧Pro】，不要错过任何有可能的提醒</li>
                <li>匹配时间超过2小时没有做出决定的匹配，将视为拒绝公开信息</li>
                <li><strong>请勿提交任何有害信息，一经发现严惩不贷！</strong></li>
            </ol>
        </div>
        <div class="cytb-btn-group cytb-column">
            <button type="button" class="btn btn-success btn-lg" style="width: 50%" id="start-matching">开始匹配
            </button>
        </div>
    </div>
    <!--#endregion 主体 end -->
@endsection

@section("js")
    <script>
        var alertModal = function (msg) {
            var modal = $("#alert-modal");
            var content = $("#modal-alert-content");
            content.text(msg);
            modal.modal("show");
        };
        $("#start-matching").on("click", function () {
            $.ajax({
                url: "/wechat/qixi/start-matching",
                dataType: "json",
                success: function (res) {
                    alertModal(res.msg);
                    //已提交信息
                    if (res.isOK) {
                        if (typeof (res.viewCode) != "undefined")
                            window.location.href = "/wechat/qixi/default-matching/" + res.viewCode;
                    }
                    //未提交信息
                    else {
                        $("#submit-info").modal("show");
                    }
                }
            });
        });
    </script>
    <script>
        <?php $url = $_SERVER["APP_URL"] . $_SERVER["REQUEST_URI"];?>
        <?php $js_config = \App\CustomClasses\Utils\WechatApi::GetJsConfig(3, $url);?>
        <?php if (!empty($js_config)){?>
        <?php echo "var js_config = " . json_encode($js_config) . ";";?>
        wx.config(js_config);
        wx.error(function (res) {
            console.log(res);
            alert("微信调用失败！");
        });
        <?php }?>
    </script>
    <script src="https://makia.dgcytb.com/js/wechat/qixi/submit_images.js"></script>
@endsection
