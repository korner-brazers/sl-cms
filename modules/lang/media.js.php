<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>Edit(id,big){
    var btn = {};
        btn['<?=$_GET['langBtn']?>'] = null;
        
    $(this).sl('_area',{
        value: ['/ajax/<?=$_GET['module']?>/edit'+(big ? 'Big' : '')+'/'+id],
        module: ['/ajax/<?=$_GET['module']?>/edit'+(big ? 'Big' : '')+'/'+id+'/1','',function(i,r){
            $(this).closest('tr').find('td:eq(2) b').text(r);
        }],
        btn:btn,
        autoclose: false,
        name: 'win<?=$_GET['module']?>',
        bg: false,
        drag: true,
        size: true
    })
}