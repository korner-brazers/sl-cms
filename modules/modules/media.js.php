<?
header("Content-type: application/x-javascript");
$lang = json_decode($_GET['lang']);
?>

function <?=$_GET['module']?>_delete(id){
    $.sl('_confirm',{
        btn:{
            '<?=$lang[6]?>':function(wn){ 
                $(this).sl('load','/ajax/<?=$_GET['module']?>/delete/'+id,function(){
                    $.sl('window',{name:wn,status:'close'});
                    $('#<?=$_GET['module']?> #tr_'+id).fadeOut(function(){
                        $(this).remove();
                        $.sl('update_scroll');
                    })
                },{back:false}); 
            }
        },
        info:'<?=$lang[7]?>!',
        autoclose:false
    });
}