var _item_image_li = '<li><img src="[URL]" alt="已选图片"></li>';
var _images = [];
var _url_creator = window.URL || window.mozURL || window.webkitURL;
var _img_modal = $("#img-modal");

_img_modal.on("hidden.bs.modal", function () {
    $("#submit-info").modal("show");
});

_img_modal.on("show.bs.modal", function () {
    $("#submit-info").modal("hide");
});

/**
 * 删除图片
 */
$("#delete-image").on("click", function () {
    if (confirm("确定删除这张照片吗？")) {
        var fileIndex = _img_modal.data("fileIndex");
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
    }
});

/**
 * 查看图片
 */
var onclick_Image = function (e) {
    var _self = $(e);

    _img_modal.data("fileIndex", _self.data("fileIndex"));
    _img_modal.data("target", _self);

    $("#modal-show-img").attr("src", _self.data("src"));

    _img_modal.modal("show");
};

/**
 * 选择图片
 */
$("#uploader-input").on("change", function () {
    var uploader_box = $("#uploader-box");
    uploader_box.fadeOut(100);

    var _self = $(this);

    var file = _self[0].files[0];

    var callback = function (base64code) {

        var src = _url_creator.createObjectURL(file) || undefined;

        if (typeof (src) == "undefined") {
            return;
        }
        console.log("压缩 : "+base64code);
        _images.push(base64code);

        var tmp_item = $(_item_image_li.replace("[URL]", src));
        tmp_item.data("fileIndex", _images.length);
        tmp_item.data("src", src);
        tmp_item.attr("onclick", "onclick_Image(this)");
        tmp_item.data("self", tmp_item);

        $("#images").append(tmp_item);

        if (_images.length < 3) {
            uploader_box.fadeIn(100);
        } else {
            uploader_box.fadeOut(100);
        }
    };

    photoCompress(file, {quality: 0.2}, callback);
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
        var formData = new FormData();

        formData.append("name", name);
        formData.append("gender", gender);
        formData.append("description", description);
        _images.forEach(function (image) {
            formData.append("image[]", convertBase64UrlToBlob(image), "file_" + Date.parse(new Date()) + ".jpg");
        });

        $.ajax({
            url: "/wechat/qixi/submit-info",
            data: formData,
            type: "post",
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.isOK) {
                    _copy_msgCode(res.msgCode);
                    alert("信息上传成功\n通信码【" + res.msgCode + "】\n已粘贴到剪切板\n请按规则向公众号回复");
                    window.location.reload();
                } else {
                    alert(res.msg);
                }
            }
        })
    }
    console.log(form);
});

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

