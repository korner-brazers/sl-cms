<?
header("Content-type: application/x-javascript");
?>
var <?=$_GET['module']?>_dat = {};
setInterval(function(){
    $.each(<?=$_GET['module']?>_dat,function(id,st){
        if(st){
             $.sl('load','/ajax/<?=$_GET['module']?>/show_result/'+id,function(d){
                <?=$_GET['module']?>_getWin(id,d)
             })
        }
    })
},2000);

function <?=$_GET['module']?>_getWin(id,d){
     $.sl('window',{name:id,bg:false,drag:true,size:true,resize:true,data:d,btn:{
        'Старт':function(){
            <?=$_GET['module']?>_dat[id] = true;
        },
        'Стоп': function(){
            <?=$_GET['module']?>_dat[id] = false;
        },
        'Обновить': function(){
           <?=$_GET['module']?>_bindWin(id);
        }
    },autoclose:false,scroll:2,status:'data',w:500,h:300,title:'Крон: '+id});
}
function <?=$_GET['module']?>_bindWin(id){
    $.sl('load','/ajax/<?=$_GET['module']?>/show_result/'+id,function(d){
       <?=$_GET['module']?>_getWin(id,d);
    })
}