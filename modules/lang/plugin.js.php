<?
header("Content-type: application/x-javascript");
?>
var <?=$_GET['module']?>TrBtn = '';

$.sl('lang','Сохранить',function(i){
     <?=$_GET['module']?>TrBtn = i;
})
    
$('span.<?=$_GET['module']?>EditBtn').live('click',function(){
    
    var btn = {},id = $(this).attr('id'),parf = $(this).closest('span.<?=$_GET['module']?>EditSpan');
        btn[<?=$_GET['module']?>TrBtn] = null;
        
    $(this).sl('_area',{
        value: ['/ajax/<?=$_GET['module']?>/editBig/'+id],
        module: ['/ajax/<?=$_GET['module']?>/editBig/'+id+'/1','',function(i,r){
            parf.html(i[0].value).append('<span class="<?=$_GET['module']?>EditBtn" id="'+id+'"></span>');
            $.sl('update_scroll');
        }],
        btn:btn,
        bg: false,
        autoclose: false,
        name: 'win<?=$_GET['module']?>',
        drag: true,
        size: true
    })
})