$(document).ready(function(){
    $("#Lists_name").focusout(function(){
        //$("#Lists_lid").load("../ajax/findUserByName");
        $.getJSON('../ajax/findUserByName', {name: $(this).val()},
            function (data){
                if (data.error) {
                    $("#Lists_lid").val("No such user");
                    $("#Lists_lid, #Lists_name").removeClass("success").addClass("error");
                }
                else {
                    $("#Lists_lid").val(data.id);
                    $("#Lists_lid, #Lists_name").removeClass("error").addClass("success")
                }
            });
    });
});