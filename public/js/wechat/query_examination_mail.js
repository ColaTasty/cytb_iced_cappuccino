$(

    $("#sub").on("click",function () {
        let ticket = $("#ticket-input").val();
        let form = $("#form");
        if (ticket.length > 0){
            form.submit();
        } else {
            alert("请输入准考证号");
        }
    })
);
