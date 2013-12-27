<?
header("Content-type: application/x-javascript");
?>
$('.ulBlocks div.layer').live('mouseover',function(){
    var mar = $(this).parents('.mar'),
        data = mar.data('po'),
        po = {};
    
    if(!data){
        po['w'] = mar.width();
        po['h'] = mar.height();
        po['p'] = mar.find('div.hidebl').height();
        mar.data('po',po);
    }
    else po = mar.data('po');
    
    mar.css({zIndex:10}).animate({top:'-15px',left:'-15px',width:po.w+30,height:po.h+30},200);
    mar.find('div.hidebl').animate({height:po.p+30},200);
    
    $(this).animate({opacity:'0.3'},200);
}).live('mouseout',function(){
    var mar = $(this).parents('.mar'),
        po = mar.data('po');
    
    mar.stop().animate({top:0,left:0,width:po.w,height:po.h,zIndex:9},200);
    mar.find('div.hidebl').stop().animate({height:po.p},200);
    
    $(this).animate({opacity:'0'},200);
});