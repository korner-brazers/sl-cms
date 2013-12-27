<?
header("Content-type: application/x-javascript");
?>

function <?=$_GET['module']?>_editor(id,tbl,_this,cid){
    $.sl('load','/ajax/<?=$_GET['module']?>/edit/'+id,function(data){
        $.sl('window',{
            data:'<form method="post" id="<?=$_GET['module']?>_form">'+data+'</form>',
            bg: false,
            title: 'Поле',
            w: 400,
            h: 176,
            btn: {
                'Сохранить': function(){
                    $.sl('load','/ajax/<?=$_GET['module']?>/edit/'+id+'/1/'+(tbl || 0)+'/'+(cid || 0),{data: $('form#<?=$_GET['module']?>_form').serializeArray()},function(data){
                        if(id){
                            _this.before(data);
                            _this.remove();
                        } 
                        else _this.before(data);
                    })
                }
            }
        })
    })
}
$('ul.field li:not(.addNew)').live('click',function(){
    var id = $(this).attr('id'),
        tbl = $(this).attr('tbl'),
        _this = $(this);
    
    $(this).sl('menu',{
        'Редактировать':function(){
            <?=$_GET['module']?>_editor(id,tbl,_this);
        },
        'Удалить':function(){
            $.sl('load','/ajax/<?=$_GET['module']?>/delete/'+id+'/'+tbl,function(){
                _this.remove();
            });
        }
    })
})

$('ul.field li.addNew').live('click',function(){
    var _this = $(this),
        tbl   = _this.attr('tbl'),
        cid   = _this.attr('cid');
    
    <?=$_GET['module']?>_editor(0,tbl,_this,cid);
});