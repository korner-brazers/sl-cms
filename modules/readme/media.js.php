<?
header("Content-type: application/x-javascript");

$lang = json_decode($_GET['lang']);
?>
$('ul.<?=$_GET['module']?>_apps_list li').live('click',function(){
    var id = $(this).attr('id'),
        dt = $(this).hasClass('data');
    
    $('#<?=$_GET['module']?>_fullReadme').fadeOut(function(){
            $.sl('load','/ajax/<?=$_GET['module']?>/loadReadme',{data:{id:id,dt:(dt ? 1 : 0)}},function(data){
            $('#<?=$_GET['module']?>_fullReadme').html(data).tinyscrollbar_remove().fadeIn().tinyscrollbar();
        })
    });
})

function <?=$_GET['module']?>_menu(){
    $.sl('big_select',{menu:[
        ['<?=$lang[1]?>','<?=$lang[2]?>'],
        ['<?=$lang[3]?>','<?=$lang[4]?> <b>data/readme</b>'],
    ]},'<?=$lang[0]?>',function(i){
        $.sl('shell',{name:'<?=$_GET['module']?>',add_param:i},'update');
    })
}