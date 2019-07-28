$(function () {
    $("#sub").on("click",function () {
        let ticket = $("#ticket-input").val();
        let form = $("#form");
        if (ticket.length > 0){
            form.submit();
        } else {
            alert("请输入准考证号");
        }
    });
});

let onclick_CopyMailNum = function () {
    $(function () {
        let mail_num = $("#mail-num").text();
        let tmp_input = $("#tmp-input");
        tmp_input.val(mail_num);
        tmp_input.select();
        document.execCommand("copy");
        alert("物流单号复制成功！");
    })
};
