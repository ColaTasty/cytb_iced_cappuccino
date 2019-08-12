var _item_image_li = '<li><img src="[URL]" alt="已选图片"></li>';
var _images = [];
var _img_modal = $("#img-modal");

_img_modal.on("hidden.bs.modal", function () {
    $("#submit-info").modal("show");
});

/**
 * 删除图片
 */
$("#delete-image").on("click", function () {
    if (confirm("确定删除这张照片吗？")) {
        var fileIndex = _img_modal.data("images_idx");
        fileIndex = Number.parseInt(fileIndex);
        console.log(fileIndex);

        if (_images.length == 0) {
            return;
        } else if (_images.length == 1) {
            _images = [];
        } else {
            var new_images = [];

            for (var i = 0; i < _images.length; i++) {
                if (i == fileIndex)
                    continue;
                new_images.push(_images[i]);
            }

            _images = new_images;
        }

        _img_modal.data("target").remove();

        _img_modal.modal("hide");

        var uploader_box = $("#uploader-box");
        if (_images.length < 3) {
            uploader_box.fadeIn(100);
        } else {
            uploader_box.fadeOut(100);
        }
    }
});

/**
 * 查看图片
 */
var previewImage = function (e) {
    var _self = $(e);
    var url = _self.data("url");
    var form = $("#submit-info");

    $("#modal-show-img").attr("src", url);
    _img_modal.data("target", _self);
    _img_modal.data("images_idx", _self.data("images_idx"));

    form.modal("hide");
    setTimeout(function () {
        _img_modal.modal("show");
    },350);
};

/**
 * 选择图片
 */
$("#uploader-box").on("click", function () {
    var uploader_box = $(this);
    wx.chooseImage({
        count: 3 - _images.length, // 默认9
        sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success: function (res) {
            var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片

            for (var local_idx = 0; local_idx < localIds.length; local_idx++) {
                var _images_index = _images.length;
                var tmp_item = $(_item_image_li.replace("[URL]", localIds[local_idx]));
                tmp_item.attr("onclick", "previewImage(this)");
                tmp_item.data("url", localIds[local_idx]);
                tmp_item.data("images_idx", _images_index);
                _images.push(localIds[local_idx]);
                $("#images").append(tmp_item);
            }

            if (_images.length < 3) {
                uploader_box.fadeIn(100);
            } else {
                uploader_box.fadeOut(100);
            }
        },
        fail: function () {
            alert("调用失败，请刷新界面重试");
        },
        complete: function () {
            console.log("调用失败");
        }
    });
});

/**
 * 复制通信码
 * @private
 */
var _copy_msgCode = function (msgCode) {
    var tmp_input = $("#tmp-input");
    tmp_input.val(msgCode);
    tmp_input.select();
    document.execCommand("copy");
};

/**
 * 上传信息
 */
$("#submit").on("click", function () {
    var form = $("#form-user-info");

    var name = form.find("#name").val();
    name = name.trim();

    var contact = form.find("#contact").val();
    contact = contact.trim();

    var gender = form.find("#gender").val();
    gender = Number.parseInt(gender);

    var description = form.find("#description").val();
    description = description.trim();

    //检查信息完整性
    if (name.length <= 0) {
        alert("请输入姓名");
        form.find("#name").focus();
        return;
    }

    if (contact.length <= 0) {
        alert("请输入联系方式");
        form.find("#contact").focus();
        return;
    }

    if (gender == -1) {
        alert("请选择性别");
        form.find("#gender").focus();
        return;
    }

    if (description.length <= 0) {
        alert("请输入自我介绍");
        form.find("#description").focus();
        return;
    }

    if (_images.length < 1) {
        alert("请选择至少一张图片");
        return;
    }

    //开始提交
    if (confirm("确定上传这些信息？\n请注意，上传后将无法更改")) {
        _upload_image(function (res) {
            var formData = new FormData();
            var files = res.files;
            files = {content: files};
            files = JSON.stringify(files);

            formData.append("name", name);
            formData.append("contact", contact);
            formData.append("gender", gender);
            formData.append("description", description);
            formData.append("image", files);

            $.ajax({
                url: "/wechat/qixi/submit-info",
                data: formData,
                type: "post",
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res.isOK) {
                        alert(res.msg);
                        if (typeof (res.viewCode) != "undefined")
                            window.location.href = "/wechat/qixi/default-matching/" + res.viewCode;
                    } else {
                        alert(res.msg);
                    }
                    $("#submit-info").modal("hide");
                }
            })
        })
    }
});

/**
 * 上传图片
 */
var _upload_image = function (next) {
    var images_files = [];

    for (var img_id_idx = 0;img_id_idx < _images.length;img_id_idx++){
        f1(img_id_idx);
    }
    function f1(i) {
        wx.uploadImage({
            localId: _images[i], // 需要上传的图片的本地ID，由chooseImage接口获得
            isShowProgressTips: 1, // 默认为1，显示进度提示
            success: function (res) {
                var serverId = res.serverId; // 返回图片的服务器端ID
                images_files.push(serverId);
                if (Number.parseInt(i) + 1 == _images.length) {
                    next({files: images_files});
                }
            },
            fail: function () {
                alert("上传图片失败，请刷新页面重试");
            },
            complete:function () {}
        });
    }
};

/*
    三个参数
    file：一个是文件(类型是图片格式)，
    w：一个是文件压缩的后宽度，宽度越小，字节越小
    objDiv：一个是容器或者回调函数
    photoCompress()
    */
function photoCompress(file, w, objDiv) {
    var ready = new FileReader();
    /*开始读取指定的Blob对象或File对象中的内容. 当读取操作完成时,readyState属性的值会成为DONE,如果设置了onloadend事件处理程序,则调用之.同时,result属性中将包含一个data: URL格式的字符串以表示所读取文件的内容.*/
    ready.readAsDataURL(file);
    ready.onload = function () {
        var re = this.result;
        canvasDataURL(re, w, objDiv)
    }
}

/**
 * 裁剪图片成Base64
 * @param path
 * @param obj
 * @param callback
 */
function canvasDataURL(path, obj, callback) {
    var img = new Image();
    img.src = path;
    img.onload = function () {
        var that = this;
        // 默认按比例压缩
        var w = that.width,
            h = that.height,
            scale = w / h;
        w = obj.width || w;
        h = obj.height || (w / scale);
        var quality = 0.7; // 默认图片质量为0.7
        //生成canvas
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        // 创建属性节点
        var anw = document.createAttribute("width");
        anw.nodeValue = w;
        var anh = document.createAttribute("height");
        anh.nodeValue = h;
        canvas.setAttributeNode(anw);
        canvas.setAttributeNode(anh);
        ctx.drawImage(that, 0, 0, w, h);
        // 图像质量
        if (obj.quality && obj.quality <= 1 && obj.quality > 0) {
            quality = obj.quality;
        }
        // quality值越小，所绘制出的图像越模糊
        var base64 = canvas.toDataURL('image/jpeg', quality);
        // 回调函数返回base64的值
        callback(base64);
    }
}

/**
 * 将以base64的图片url数据转换为Blob
 * @param urlData
 */
function convertBase64UrlToBlob(urlData) {
    var arr = urlData.split(','),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], {
        type: mime
    });
}

