<?php
/**
 * @author Makia98 https://github.com/ColaTasty
 * Created On 2019-07-28 13:56
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>表单测试</title>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.js"></script>
</head>
<body>
<div id="content">
    <form action="./">
        @csrf
        <label for="str">搜索</label><input id="str" type="text">
        <input id="sub" type="button" value="提交">
    </form>
</div>
<script>
    $(
        $("#sub").on("click", function (e) {
            let str = $("#str");
            let val = str.val();
            $.ajax({
                url: "https://www.baidu.com/s?ie=UTF-8&wd=" + encodeURI(val),
                dataType:"html",
                success:function (res) {
                    console.log(res);
                }
            })
        })
    )
</script>
</body>
</html>
