<?
header("Content-type: application/x-javascript");

$lang = json_decode($_GET['lang']);
?>
function <?=$_GET['module']?>_restoreBackup(id,delet,_this){
    $.sl('_confirm','<?=$lang[3]?>?',{error:true,h:70,title: '<?=$lang[20]?>'},function(){
        $.sl('load','/ajax/<?=$_GET['module']?>/restoreBackup/'+(delet ? 1 : 0),{data:{id:id}},function(){
            delet && $(_this).sl('_tbl_del_tr');
        });
        
    })
}
function <?=$_GET['module']?>_menu(){
    var ajUrl = '/ajax/<?=$_GET['module']?>/';
    
    $.sl('big_select',{menu:[
        ['<?=$lang[4]?>','<?=$lang[5]?>'],
        ['<?=$lang[6]?>','<?=$lang[7]?>'],
        ['<?=$lang[8]?>','<?=$lang[9]?>']
    ]},'<?=$lang[10]?>',function(i){
        if(i == 0){
            $.sl('_area',{
                value:[ajUrl+'getFolder'],
                module: [ajUrl+'getFolder/save'],
                autoclose: false,
                title: '<?=$lang[19]?>',
                btn: {
                    '<?=$lang[11]?>':null
                }
            })
        }
        else if(i == 1){
            $.sl('_confirm',{
                info: '<?=$lang[12]?>?',
                error: true,
                title: '<?=$lang[20]?>'
            },function(){
                $.sl('load',ajUrl+'fullScan',function(){
                    $.sl('shell',{name:'<?=$_GET['module']?>'},'update');
                })
            })
            
        }
        else if(i == 2){
            $.sl('load',ajUrl+'backup',{
                win:{
                    bg:false,
                    autoclose: false,
                    w: 700,
                    h: 450,
                    title: '<?=$lang[8]?>',
                    btn: {
                        '<?=$lang[13]?>':function(w){
                            
                            $.sl('big_select',{menu:[
                                ['<?=$lang[14]?>','<?=$lang[15]?>'],
                                ['<?=$lang[16]?>','<?=$lang[17]?>']
                            ]},'<?=$lang[17]?>',function(i){
        
                                $.sl('load',ajUrl+(i == 0 ? 'createBackup' : 'createBackupChange'),function(data){
                                    $.sl('window',{name:w,status:'data',data:data});
                                })
                            })
                        }
                    }
                }
            })
        }
    })
}