function refreshName(){
    $.getJSON('index.php?r=ajax/findUserByName', {name: $("#Lists_name").val()},
        function (data){
            if (data.error) {
                $("#Lists_lid").val("No such user");
                $("#Lists_lid, #Lists_name").parent().parent().removeClass("success").addClass("error")
            }
            else {
                $("#Lists_lid").val(data.id);
                $("#Lists_name").val(data.username);
                $("#Lists_lid, #Lists_name").parent().parent().removeClass("error").addClass("success")
            }
        });
}

$(document).ready(function(){

    $("#Lists_lid").after('<a class="btn" id="unlockLid"><i class="icon-lock"></i> </a>');
    $("#Lists_name").after('<a class="btn" id="refreshName"><i class="icon-refresh"></i> </a>')
        .focusout(refreshName);

    $("#unlockLid, button[type=submit]").click(function(){
        $("#Lists_lid").removeAttr('disabled');
    });
    $("#refreshName").click(refreshName);
});