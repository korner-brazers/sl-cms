<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>_get_install(id){
    $.sl('load','/ajax/<?=$_GET['module']?>/install_module/'+id,{mode:'hide',dataType:'json'},function(j){
        var cont = $('.install_box_install');
        cont.eq(0).fadeOut();
        
        if(j.error){
            cont.eq(2).fadeIn();
            $.sl('info',j.error);
        }
        else cont.eq(1).fadeIn();
    });
}