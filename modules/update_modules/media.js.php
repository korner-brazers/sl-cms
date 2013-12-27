<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>(id,tbl){
    var eq = $(this).closest('tr').find('td').eq(2);
    
    $('.error,.ok',eq).hide();
    
    $('.load',eq).fadeIn(function(){
        $.sl('load','/ajax/market/install_module/'+id,{mode:'hide',ignore:true,dataType:'json'},function(j){
            $('.load',eq).fadeOut(function(){
                if(j.success) $('.ok',eq).fadeIn();
                else $('.error',eq).fadeIn();
            });
        })
    });
    
}