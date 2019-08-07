<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-06 22:59
 */

use \App\WeChatQixiUser as User;
use \App\WeChatQixiMatchingResult as Result;
?>

<?php
$result = isset($result) ? $result : null;
if (empty($result)) {
    return redirect("error", ["msg" => "结果查找失败"]);
}
$other_open_id = $result->other_open_id;
$other_user = User::where("open_id", $other_open_id)->first();
if (empty($other_user)) {
    return redirect("error", ["msg" => "用户查找失败"]);
}
$other_result = Result::where("open_id", $other_open_id)->where("other_open_id", $result->open_id)->first();
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!--#endregion    模态弹窗 图片 end    -->
    <!--#region   模态弹窗 对方匹配信息  -->
    <?php if (isset($other_open_id)) {
    $other_user = \App\WeChatQixiUser::where("open_id", $other_open_id)->first(); ?>
    <div class="modal" id="info-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">对方信息</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">称呼</label><br>
                        <input id="name" class="form-control" name="name" type="text" placeholder="加载失败"
                               style="width: 100%;" readonly value="<?php echo $other_user->name;?>">
                    </div>
                    <div class="form-group">
                        <label for="name">联系方式</label><br>
                        <input id="name" class="form-control" name="contact" type="text" placeholder="加载失败"
                               style="width: 100%;" readonly value="<?php echo $other_user->contact;?>">
                    </div>
                    <div class="form-group">
                        <label for="description">自我介绍</label><br>
                        <textarea name="description" class="form-control" id="description"
                                  placeholder="自我介绍里面一定要写上联系方式噢！"
                                  maxlength="300" style="width: 100%"
                                  rows="5" readonly><?php echo $other_user->description;?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php }?>
    <!--#endregion   模态弹窗 对方匹配信息  -->
    <!--#region 主体 -->
    <div class="cytb-content">
        <!--#region 滚动画廊 -->
        <div id="carousel-example-generic" class="carousel slide cytb-carousel" data-ride="carousel">
            <!-- 图片定位点 -->
            <ol class="carousel-indicators">
                <?php
                $image_url = $other_user->image;
                $image_url = json_decode($image_url);
                $image_url = $image_url->image_url;
                ?>
                <?php $image_index = 0; ?>
                <?php foreach ($image_url as $image){?>
                <li data-target="#carousel-example-generic"
                    data-slide-to="{{$image_index}}" class="{{$image_index==0?"active":""}}"></li>
                <?php $image_index++;}?>
            </ol>

            <!-- 图片 -->
            <div class="carousel-inner" role="listbox">
                <?php $url_index = 0; ?>
                <?php foreach ($image_url as $url){ ?>
                <div class="item{{$url_index==0?" active":""}}">
                    <div class="img-box">
                        <img class="img-responsive"
                             src="{{$url}}"
                             alt="图片{{$url_index+1}}">
                    </div>
                </div>
                <?php $url_index++;}?>
            </div>
            <!-- 前后控制 -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <!--#endregion 滚动画廊 end -->
        <!--#region 按钮 -->
        <div class="cytb-btn-group cytb-column" style="margin-top: 20px">
            <?php
            $status = $result->status;
            $other_status = isset($other_result->status) ? $other_result->status : 0;
            ?>
            <button type="button" class="btn btn-danger btn-lg" onclick="next_matching()" style="width: 50%" id="next-matching">
                匹配下一个
            </button>
            @if($status == 0)
                <button type="button" class="btn btn-success btn-lg" style="width: 50%" id="want-matching">
                    提出交换信息
                </button>
            @elseif($status == 1 && $other_status == 0)
                <button type="button" class="btn btn-success btn-lg" style="width: 50%"
                        disabled>
                    等待对方同意
                </button>
            @else
                <button type="button" data-toggle="modal" data-target="#info-modal"
                        class="btn btn-success btn-lg" style="width: 50%" id="success-matching">
                    查看对方资料
                </button>
            @endif
        </div>
        <!--#endregion 按钮 end -->
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
        var next_matching = function () {
            $.ajax({
                url: "/wechat/qixi/start-matching/",
                dataType: "json",
                success: function (res) {
                    //已提交信息
                    if (res.isOK) {
                        window.location.href = "/wechat/qixi/default-matching/" + res.viewCode;
                    }
                    //未提交信息
                    else {
                        alertModal(res.msg);
                        $("#submit-info").modal("show");
                    }
                },
                error:function (XHR,status,content) {
                    alertModal("匹配出错了，请稍等一下，或从活动入口重新进入");
                }
            });
        };
        var want_matching = function () {
            $.ajax({
                url: "/wechat/qixi/want-matching/<?php echo $result->view_code;?>",
                dataType: "json",
                success: function (res) {
                    alertModal(res.msg);
                }
            });
        };
        $("#next-matching").on("click", next_matching);
        $("#want-matching").on("click", want_matching);
        $(".carousel-inner img").on("click", function (e) {
            var img = $(this);
            var modal = $("#img-modal");
            var show_img = $("#modal-show-img");
            show_img.attr("src", img.attr("src"));
            modal.modal("show");
        });
    </script>
    @if($status == 1 && $other_status == 1)
        <script>
            alertModal("恭喜你匹配成功了！快查看信息获取联系方式吧！");
        </script>
    @endif
@endsection


