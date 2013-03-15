function refreshName(){
    $.getJSON('../ajax/findUserByName', {name: $("#Lists_name").val()},
        function (data){
            if (data.error) {
                $("#Lists_lid").val("No such user");
                $("#Lists_lid, #Lists_name").removeClass("success").addClass("error");
            }
            else {
                $("#Lists_lid").val(data.id);
                $("#Lists_name").val(data.username);
                $("#Lists_lid, #Lists_name").removeClass("error").addClass("success")
            }
        });
}

$(document).ready(function(){

    $("#Lists_lid").after('<a class="btn" id="unlockLid"><i class="icon-lock"></i> </a>');
    $("#Lists_name").after('<a class="btn" id="refreshName"><i class="icon-refresh"></i> </a>');

    $("#unlockLid, button[type=submit]").click(function(){
        $("#Lists_lid").removeAttr('disabled');
    });
    $("#refreshName").click(refreshName);
    $("#Lists_name").focusout(refreshName);
});