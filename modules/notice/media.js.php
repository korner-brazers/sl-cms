<?
header("Content-type: application/x-javascript");
?>
setInterval(function(){
    $.sl('load','/ajax/<?=$_GET['module']?>/show_prew',{mode:'hide',ignore:true,dataType:'json'},function(j){
        if(j.error) $.sl('info',j.error);
    });
},5000);