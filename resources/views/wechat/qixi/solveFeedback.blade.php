<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-07 20:26
 */
?>

<?php
if (!isset($feedback)) {
    return view("error", ["msg" => "不存在的反馈"]);
}
$result = \App\WeChatQixiMatchingResult::where("open_id", $feedback->open_id)
    ->where("view_code", $feedback->view_code)
    ->first();
if (empty($result)) {
    echo "不存在的匹配结果 <a href='javascript:location.back(-1)'>返回</a>";
    return;
}
$user = \App\WeChatQixiUser::where("open_id", $result->open_id)->first();
if (empty($user)) {
    echo "不存在的反馈者";
    return;
}

$other_user = \App\WeChatQixiUser::where("open_id", $result->other_open_id)->first();
if (empty($other_user)) {
    echo "不存在的被反馈者";
    return;
}
#region 反馈者信息
$json_user = [
    "open_id" => $user->open_id,
    "name" => $user->name,
    "gender" => $user->gender == 0 ? "女" : "男",
    "contact" => $user->contact,
    "description" => $user->description,
    "status" => $user->status,
];
$user_image = json_decode($user->image)->image_url;
$json_user["image"] = $user_image;
$json_user = json_encode($json_user);
#endregion

#region 被反馈者信息
$json_other_user = [
    "open_id" => $other_user->open_id,
    "name" => $other_user->name,
    "gender" => $other_user->gender == 0 ? "女" : "男",
    "contact" => $other_user->contact,
    "description" => $other_user->description,
    "status" => $other_user->status,
];
$other_user_image = json_decode($other_user->image)->image_url;
$json_other_user["image"] = $other_user_image;
$json_other_user = json_encode($json_other_user);
#endregion
$json_user = str_replace(" ", "", $json_user);
$json_other_user = str_replace(" ", "", $json_other_user);
?>
@extends("wechat.qixi.component")

@section("title")
    活动反馈
@endsection

@section("css")
    <style>
        .cytb-btn-group {

        }

        .cytb-btn-group button {

        }

        .cytb-image-box {
            width: 100%;
        }

        .cytb-image-box img {
            width: 100%;
            margin: 0 auto;
        }

        ul.cytb-images {
            list-style: none;
            min-height: 48px;
        }

        ul.cytb-images li {
            float: left;
            margin: 5px;
            width: 48px;
            height: 48px;
            overflow: hidden;
        }

        ul.cytb-images li img {
            float: left;
            margin: 5px;
            width: 100%;
            border: 1px solid #aaaaaa;
        }
    </style>
    <script>
        <?php echo("var _user = " . $json_user . ";")?>
        <?php echo("var _other_user = " . $json_other_user . ";")?>
    </script>
@endsection

@section("content")
    <div class="cytb-content">
        <!--#region    模态弹窗 图片    -->
        <div class="modal" id="img-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="cytb-image-box"><img src="" alt="" id="modal-img"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--#endregion    模态弹窗 图片 end    -->
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
                                onclick="onclick_Confirm()" data-dismiss="modal">确定
                        </button>
                        <button type="button" class="btn btn-default" id="modal-alert-cancel" onclick="onclick_Cancel()"
                                data-dismiss="modal">关闭
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--#endregion    模态弹窗 通知 end    -->
        <!--#region 信息-->
        <div class="panel panel-default" id="form">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active" onclick="onclick_User(this)"><a
                            href="javascript:void(0)">反馈者</a></li>
                    <li role="presentation" onclick="onclick_OtherUser(this)"><a href="javascript:void(0)">被反馈者</a></li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label for="name">称呼</label>
                    <input type="text" class="form-control" id="name" readonly>
                </div>
                <div class="form-group">
                    <label for="gender">性别</label>
                    <input type="text" class="form-control" id="gender" readonly>
                </div>
                <div class="form-group">
                    <label for="contact">联系方式</label>
                    <input type="text" class="form-control" id="contact" readonly>
                </div>
                <div class="form-group">
                    <label for="description">自我介绍</label>
                    <textarea class="form-control" id="description" style="width: 100%" rows="5" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="images">图片</label>
                    <ul class="cytb-images" id="images"></ul>
                </div>
                <div class="form-group">
                    <label for="status">状态</label>
                    <input type="text" class="form-control" id="status" readonly>
                </div>
            </div>
            <div class="panel-footer">修改状态 :
                <button type="button" class="btn btn-danger btn-xs" onclick="onclick_ChangeStatus(2)">封禁</button>
                <button type="button" class="btn btn-primary btn-xs" onclick="onclick_ChangeStatus(3)">修改信息</button>
                <button type="button" class="btn btn-success btn-xs" onclick="onclick_ChangeStatus(1)">恢复匹配</button>
                <button type="button" class="btn btn-default btn-xs" onclick="onclick_ChangeStatus(4)">停止匹配</button>
            </div>
        </div>
        <!--#endregion 信息-->
    </div>
@endsection

@section("js")
    <script>
        var _images = [];
        var _status = ["未提交信息", "正常匹配", "封禁", "重新提交信息", "暂时不可匹配"];
        var _imageDom = "<li><img src=\"[URL]\" alt=\"图片\" onclick='onclick_PreviewImage(this)'></li>";
        /**
         *
         * @param msg
         * @param confirm
         * @param cancel
         * @constructor
         */
        var ShowAlertModal = function (msg, confirm = function () {
        }, cancel = function () {
        }) {
            $("#modal-alert-msg").text(msg);
            $("#alert-modal").modal("show");
            onclick_Confirm = confirm;
            onclick_Cancel = cancel;
        };
        /**
         *
         */
        var onclick_Confirm = function () {

        };
        /**
         *
         */
        var onclick_Cancel = function () {

        };
        /**
         *
         * @param image
         * @private
         */
        var _ResfreshImages = function (image) {
            $("#images").empty();
            image.forEach(function (url) {
                var tmp_str = _imageDom.replace("[URL]", url);
                var tmp_item = $(tmp_str);
                $("#images").append(tmp_item);
            });
        };
        /**
         *
         * @param e
         */
        var onclick_PreviewImage = function (e) {
            var _self = $(e);
            $("#modal-img").attr("src", _self.attr("src"));
            $("#img-modal").modal("show");
        };
        /**
         *
         */
        var onclick_ChangeStatus = function (statusIdx) {
            var user = $("#form").data("user");
            var msg = [
                "未知状态",
                "确定恢复\"" + user.name + "\"匹配吗？",
                "确定封禁\"" + user.name + "\"吗？",
                "确定让\"" + user.name + "\"重新提交信息吗？",
                "确定让\"" + user.name + "\"停止匹配吗？"
            ];
            ShowAlertModal(msg[statusIdx], function () {
                if (user.status == statusIdx) {
                    alert("TA已经是这个状态了");
                    return;
                }
                $.ajax({
                    url: "/wechat/qixi/set-status/" + user.open_id + "/" + statusIdx,
                    method: "post",
                    success: function (res) {
                        alert(res.msg);
                        if (res.isOK) {
                            location.href = "/wechat/qixi";
                        }
                    },
                    fail: function () {
                        ShowAlertModal("网络出错了，请晚些再试");
                    }
                })
            });
        };
        /**
         *
         * @param user
         * @private
         */
        var _RefreshFrom = function (user) {
            $("#name").val(user.name);
            $("#gender").val(user.gender);
            $("#contact").val(user.contact);
            $("#description").val(user.description);
            _images = user.image;
            _ResfreshImages(user.image);
            $("#status").val(_status[user.status]);
            $("#form").data("user", user);
        };
        /**
         *
         * @param e
         */
        var onclick_User = function (e) {
            var _self = $(e);
            $(".nav.nav-tabs li").removeClass("active");
            _self.addClass("active");
            _RefreshFrom(_user);
        };
        /**
         *
         * @param e
         */
        var onclick_OtherUser = function (e) {
            var _self = $(e);
            $(".nav.nav-tabs li").removeClass("active");
            _self.addClass("active");
            _RefreshFrom(_other_user);
        };
        _RefreshFrom(_user);
    </script>
    <script>
        <?php $url = $_SERVER["APP_URL"] . $_SERVER["REQUEST_URI"];?>
        <?php $js_config = \App\CustomClasses\Utils\WechatApi::GetJsConfig(3, $url);?>
        @if(!empty($js_config))
        <?php echo "var js_config = JSON.parse('" . json_encode($js_config) . "');";?>
        wx.config(js_config);
        wx.error(function (res) {
            console.log(res);
            alert("微信调用失败！");
        });
        @endif
    </script>
@endsection
