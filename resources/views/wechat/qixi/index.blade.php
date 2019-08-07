<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-08-04 15:19
 */
?>

@extends('wechat.qixi.component')

@section("css")
    <style>
        /** carousel **/
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
            height: 100%;
            margin: 0 auto;
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

        .cytb-rule {
            /*width: 80%;*/
            margin: 10px;
            min-height: 50px;
            padding: 5px 5px 10px 25px;
            color: #FFFFFF;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }

        .cytb-rule li {
            margin-top: 3px;
        }

        #pictureModal .modal-dialog {
            /*width: 90%;*/
        }

        #modal-show-img {
            width: 100%;
            margin: 0 auto;
        }

        .modal-body .img-box {
            width: 90%;
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
    <!-- 模态弹窗 -->
    <div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="img-box"><img id="modal-show-img" src="" alt=""></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 模态弹窗end -->
    <!-- 网页content -->
    <div class="cytb-content">
        <!-- 滚动画廊 -->
        <div id="carousel-example-generic" class="carousel slide cytb-carousel" data-ride="carousel">
            <!-- 图片定位点 -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            </ol>

            <!-- 图片 -->
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <div class="img-box">
                        <img class="img-responsive"
                             src="https://makia.dgcytb.com/image/qixi_background2.jpg" alt="xxx">
                    </div>
                    <!--<div class="carousel-caption"></div>-->
                </div>
                <div class="item">
                    <div class="img-box">
                        <img class="img-responsive"
                             src="https://makia.dgcytb.com/image/qixi_background.jpg" alt="xxx">
                    </div>
                    <!--<div class="carousel-caption"></div>-->
                </div>
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
        <!-- 滚动画廊end -->
        <!-- 按钮 -->
        <div class="cytb-btn-group cytb-column" style="margin-top: 20px">
            <button type="button" class="btn btn-success btn-lg" onclick="onclick_DefaultMatching(this)"
                    style="width:50%;margin-top: 10px;">参加匹配
            </button>
        </div>
        <!-- 按钮end -->
        <!-- 同意 -->
        <div class="checkbox text-center" style="margin-top: 20px;color: #006bff">
            <label>
                <input type="checkbox" id="accept-rule"> 我已悉知并同意活动规则
            </label>
        </div>
        <!-- 同意end -->
        <!-- 规则 -->
        <div class="cytb-rule">
            <ol>
                <li>用户关注【城院贴吧Pro】公众号后方可参加。</li>
                <li>填写联系信息和<s>照骗</s>后，即可加入匹配队列</li>
                <li>匹配过程中，如发现有害的信息，请向公众号回复【七夕反馈】，接着按照指示提交访问链接即可</li>
                <li><strong>请各位用户谨防上当受骗！凡是有涉及暴力、色情、血腥、宗教色彩等违反了相关公众安全管理条例的信息被发现，请立即向城院贴吧举报！对于恶意破坏活动环境者，严惩不贷！</strong>
                </li>
            </ol>
        </div>
        <!-- 规则end -->
    </div>
    <!-- 网页content end -->
@endsection

@section("js")
    <script>
        $(".carousel-inner img").on("click", function (e) {
            var img = $(this);
            var modal = $("#pictureModal");
            var show_img = $("#modal-show-img");
            show_img.attr("src", img.attr("src"));
            modal.modal("show");
        });

        /**
         *
         * @returns {boolean}
         * @private
         */
        var _check_IsAcceptRule = function () {
            return $("#accept-rule").is(":checked");
        };

        /**
         *
         * @param {document} item
         */
        var onclick_RealTimeMatching = function (item) {
            if (!_check_IsAcceptRule()) {
                alert("请悉知并同意活动规则！");
                return;
            }
        };

        /**
         *
         * @param {document} item
         */
        var onclick_DefaultMatching = function (item) {
            if (!_check_IsAcceptRule()) {
                alert("请悉知并同意活动规则！");
                return;
            }
            window.location.href = "/wechat/qixi/default-matching";
        };
    </script>
@endsection
