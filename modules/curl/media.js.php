<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>_new(call){
    $.sl('_promt',{input:{'name':{regex:'[^0-9a-z]'}},btn:{'Добавить':function(w,va){
        $.sl('load','/ajax/<?=$_GET['module']?>/addnew/'+<?=$_GET['module']?>_catid,{data:{name:va[0].value}},function(data){
            call && call(data);
        })
    }}});
}