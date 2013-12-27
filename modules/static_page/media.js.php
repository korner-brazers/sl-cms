<?
header("Content-type: application/x-javascript");
?>
$('#<?=$_GET['module']?>_table #edit_page').live('click',function(){
    var id = $(this).attr('cid');
        
    $(this).sl('menu',{'Изменить':'$.sl(\'_promt\',{load:[\'/ajax/<?=$_GET['module']?>/edit/'+id+'/title/1\'],module:[\'/ajax/<?=$_GET['module']?>/save/'+id+'/title\',\'\',function(d){ $(\'#<?=$_GET['module']?>_table #tr_'+id+' td:eq(4) b\').text(d[0].value) }],title:\'Редактировать\'});','Просмотр':'window.open(\'/static_page/full/'+id+'\',\'_blank\')'},{zIndex:600});
})

$('#<?=$_GET['module']?>_table #edit_descr').live('click',function(){
    var id = $(this).attr('cid');
    
    $.sl('_area',{area_name:0,value:['/ajax/<?=$_GET['module']?>/edit/'+id+'/descr'],module:['/ajax/<?=$_GET['module']?>/save/'+id+'/descr','',function(d){
        $('#<?=$_GET['module']?>_table #tr_'+id+' td:eq(5) span').text(d[0].value.substr(0,100));
    }],autoclose:false,btn:{
        'Сохранить':''
    },title:'Описание'});
})

$('#<?=$_GET['module']?>_table #edit_cid').live('click',function(){
    var id = $(this).attr('cid');
    
    $.sl('_promt',{load:['/ajax/<?=$_GET['module']?>/edit/'+id+'/cid/1'],module:['/ajax/<?=$_GET['module']?>/save/'+id+'/cid','',function(d){
        $('#<?=$_GET['module']?>_table #tr_'+id+' td:eq(1) b').text(d[0].value);
    }],autoclose:false,btn:{
        'Сохранить':''
    },title:'CID'});
})

function <?=$_GET['module']?>_chCid(i,id){
    $.sl('load','/ajax/<?=$_GET['module']?>/chCid/'+id+'/'+i);
}