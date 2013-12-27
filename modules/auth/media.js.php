<?
header("Content-type: application/x-javascript");
?>
/**
 * @de_auth
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
var users = 0;
var DSS = (function($, window, document, undefined) {
    var point = 1;
    
    return {
        init: function() {
            
            $('#bg_img').load(function(){
                setTimeout(function(){
                    $('#bg').addClass('bgload');
                },1000)
            })
            
            Cufon.replace(".smooth");
            
            $('#bg_blur').load(function(){
                setTimeout(function(){
                    $('#install').fadeIn();
                    
                    if(users > 1){
                        $('.point_right').show();
                    }
                },1000);
            })
            
            $('.p_r').click(function(){
                point += 1;
                point > 1 && $('.point_left').fadeIn();
                var parent = $('.all_users').css({marginLeft:'-'+((point-1)*258)+'px'});
                $('li',parent).removeClass('active');
                $('input',parent).attr('disabled','');
                $('li',parent).eq(point-1).addClass('active').find('input').removeAttr('disabled');
                point >= users && $('.point_right').fadeOut();
            })
            $('.p_l').click(function(){
                point -= 1;
                point <= 1 && $('.point_left').fadeOut();
                var parent = $('.all_users').css({marginLeft:'-'+((point-1)*258)+'px'});
                $('li',parent).removeClass('active');
                $('input',parent).attr('disabled','');
                $('li',parent).eq(point-1).addClass('active').find('input').removeAttr('disabled');
                point <= users && $('.point_right').fadeIn();
            })
            
            $(window).resize(function() {
                DSS.setSize();
            })
        },
        setSize: function(){
            $('#win_bg,#wrap,#bg').css({width:window.innerWidth,height:window.innerHeight});
        }
    };
})(jQuery, this, this.document);

jQuery(document).ready(function() {
  DSS.init();
});