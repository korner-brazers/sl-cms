<?
header("Content-type: application/x-javascript");
$lang = json_decode($_GET['lang']);
?>
function <?=$_GET['module']?>_add(fn,_this){
    $(_this).sl('menu',{
        '<?=$_GET[14]?>':function(){
            $.sl('load','/ajax/<?=$_GET['module']?>/list_show',function(data){
                $.sl('window',{name:'<?=$_GET['module']?>_win',data:data,w:500,h:300,preload:false,autoclose:false});
            })
        },
        '<?=$_GET[15]?>':function(){
            $.sl('load','/ajax/<?=$_GET['module']?>/list_show/1',function(data){
                $.sl('window',{name:'<?=$_GET['module']?>_win',data:data,w:500,h:300,preload:false,autoclose:false});
            })
        }
    },{position:'cursor'})
}
function <?=$_GET['module']?>_add_new(data){
    $('#<?=$_GET['module']?>_table tr:eq(0)').after(data);
    $.sl('update_scroll');
}