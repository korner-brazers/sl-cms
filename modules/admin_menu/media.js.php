<?
header("Content-type: application/x-javascript");
?>

function <?=$_GET['module']?>_start(){
    var hold = false;
    
    setTimeout(function(){ 
        $.metro('ready','m_i'); 
        $.metro('start','m_i'); 
        $.metro('ready','m_w'); 
        $.metro('start','m_w');
        
        $('.a_m_bl .metro td div.lay').hammer().on('hold touchstart',function(event){
            event.preventDefault();
            
            if(event.type == 'hold'){
                hold = true;
                
                var elP = $(this).offset(),
                    elOf = {
                        w: $(this).outerWidth(),
                        h: $(this).outerHeight(),
                        x: elP.left,
                        y: elP.top
                    },
                    mns = $(this).attr('modname'),
                    type = $(this).attr('type');
    
                $.sl('shell',{name:'<?=$_GET['module']?>'},'hide_all',function(){
                    DSS.create({
                        x:elOf.x + (elOf.w / 2),
                        y:elOf.y + (elOf.h / 2),
                        name:mns,
                        type:type 
                    });
                    
                    
                });
                
                setTimeout(function(){
                    hold = false;
                },3000);
            }
        })
    },500);
    
    $('.a_m_bl .metro td div.lay').on('mouseup touchend',function(){
        if(!hold) $.sl('shell',{name:$(this).attr('modname'),mode:'hide'});
    })
}