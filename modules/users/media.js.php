<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>_edit_row(id,row){
    var _this = $(this);
    
    $.sl('load','/ajax/<?=$_GET['module']?>/edit_row/get/'+id+'/'+row,function(data){
        if(row == 'login' || row == 'email'){
            $.sl('_promt',{input:[{value:data}],module:['/ajax/<?=$_GET['module']?>/edit_row/set/'+id+'/'+row,'',function(i){
                _this.text(i[0].value);
            }],btn:{'Сохранить':null},autoclose:false})
        }
        else{
            $.sl('_area',{value:data,area_name:'0',module:['/ajax/<?=$_GET['module']?>/edit_row/set/'+id+'/'+row,'',function(i){
                _this.text(i[0].value);
            }],btn:{'Сохранить':null},autoclose:false})
        }
    })
}
function <?=$_GET['module']?>_edit_row_group(id,row){
    var _this = $(this);
    
    $.sl('load','/ajax/<?=$_GET['module']?>/edit_row_group/get/'+id+'/'+row,function(data){
        if(row == 'name'){
            $.sl('_promt',{input:[{value:data}],module:['/ajax/<?=$_GET['module']?>/edit_row_group/set/'+id+'/'+row,'',function(i){
                _this.text(i[0].value);
            }],btn:{'Сохранить':null},autoclose:false})
        }
        else if(row == 'list_module'){
            $.sl('_area',{value:data,area_name:'0',module:['/ajax/<?=$_GET['module']?>/edit_row_group/set/'+id+'/'+row,'',function(i){
                _this.text(i[0].value);
            }],btn:{'Сохранить':null},autoclose:false})
        }
    })
}
function <?=$_GET['module']?>_addnewuser(call,_this){
    $.sl('load','/ajax/<?=$_GET['module']?>/addnewuser',function(data){
        $.sl('window',{
            title: 'Новый пользователь',
            autoclose: false,
            w: 550,
            h: 350,
            data: data,
            preload: false,
            btn: {
                'Добавить': function(w){
                    $.sl('load','/ajax/<?=$_GET['module']?>/addnewuser/set',{data:$('#<?=$_GET['module']?>_addUser').serializeArray()},function(data){
                        call(data);
                        $.sl('window',{name:w,status:'close'});
                    })
                }
            }
        })
    });
}