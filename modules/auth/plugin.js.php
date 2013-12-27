<?
header("Content-type: application/x-javascript");
?>
var <?=$_GET['module']?>PluginInclude = true;

function <?=$_GET['module']?>InitPlugin(){
    var wdj = $('.authWidget');
    var url = '/ajax/<?=$_GET['module']?>/'
    
    setTimeout(function(){
        $('.loadAuth',wdj).slideDown();
        $('.preloadAuth',wdj).slideUp()
    },1000);
    
    $('.btnLogin',wdj).click(function(){
        var ser = $('form#authForm',wdj).serializeArray();
        
        if(ser[0].value == '' || ser[1].value == ''){
            $.sl('info',<?=$_GET['module']?>Lang[0]);
            return;
        }
        
        $.sl('load',url+'login',{data:ser},function(){
            window.location = document.URL;
        })
    })
    
    $('.btnOut',wdj).click(function(){
        $(this).sl('load',url+'logout',{back:false},function(){
            window.location = document.URL;
        })
    })
    
    $('.btnReg',wdj).click(function(){
        var btn = {};
            btn[<?=$_GET['module']?>Lang[1]] = function(w){
                var ser = $('form#authNewRegistr').serializeArray();
                
                if(ser[0].value == '' || ser[1].value == '' || ser[2].value == ''){
                    $.sl('info',<?=$_GET['module']?>Lang[0]);
                    return;
                }
                
                $(this).sl('load',url+'registrNew/1',{data:ser,back:false},function(){
                    $.sl('window',{name:w,status:'close'},function(){
                        window.location = document.URL;
                    });
                })
            };
            
        $.sl('load',url+'registrNew',function(data){
            $.sl('window',{
                title: <?=$_GET['module']?>Lang[1],
                data: '<form id="authNewRegistr" method="post">'+data+'</form>',
                w: 300,
                h: 140,
                autoclose: false,
                btn: btn
            })
        })
    })
}