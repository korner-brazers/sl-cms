<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>_add(fn,_this){
    $.sl('_promt',{input:[{}],
        title: 'Название',
        btn:{
        'Добавить':function(w,i){
            if(i[0].value == '') $.sl('info','Название пустое');
            else{
                $.sl('load','/ajax/<?=$_GET['module']?>/addnew',{data:{name:i[0].value}},function(data){
                    $('#<?=$_GET['module']?>_table tr:eq(0)').after(data);
                })
            }
        }
    }})
}
function <?=$_GET['module']?>_change(id){
    $(this).sl('_promt',{load:['/ajax/<?=$_GET['module']?>/change/'+id,'quiet'],module:['/ajax/<?=$_GET['module']?>/change/'+id+'/save','',function(i){
        $(this).text(i[0].value == '' ? '---' : i[0].value);
    }]});
}
function <?=$_GET['module']?>_menu(id){
    $(this).sl('scroll_menu',{load:['/ajax/<?=$_GET['module']?>/podcat/'+id,'quiet'],module:['/ajax/<?=$_GET['module']?>/podcat/'+id+'/save']},function(i,data){
        $(this).text(data); 
    })
}