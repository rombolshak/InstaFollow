$(document).ready(function(){

    setInterval(function(){
        $("#lists-grid").yiiGridView('update');
    }, 5000);

 });