<?
header("Content-type: application/x-javascript");
?>

/**
 * @metro
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
(function($){
    
    $('.metro div.shine').live('mouseover',function(){
        var lay = $(this).closest('.lay'),
            data = lay.data('po'),
            po = {};
        
        if(!data){
            po['w'] = lay.width();
            po['h'] = lay.height();
            po['p'] = lay.find('div.hidebl').height();
            lay.data('po',po);
        }
        else po = lay.data('po');
        
        lay.css({zIndex:10}).animate({top:'-15px',left:'-15px',width:po.w+30,height:po.h+30},200);
        lay.find('div.hidebl').animate({height:po.p+30},200);
        
        $(this).addClass('hover');
    }).live('mouseout',function(){
        var lay = $(this).closest('.lay'),
            po = lay.data('po');
        
        lay.stop().animate({top:0,left:0,width:po.w,height:po.h,zIndex:9},200);
        lay.find('div.hidebl').stop().animate({height:po.p},200);
        
        $(this).removeClass('hover');
    });
    
    function m_in(id){
        
        var m_c = $('.metro_conteiner'+(id ? '#'+id : '')),
            m_p = $((id ? '#'+id+' ' : '')+'.metro_page'),
            m_m = $((id ? '#'+id+' ' : '')+'.metro'),
            m_r = m_c.parent();
            
        return [m_c,m_p,m_m,m_r];
    }
    
    var methods = {
        ready : function(id) {
            $.sl('resize',function(){
                var m = m_in(id);
                
                function setSize(){
                    m[0].css({paddingTop:(m[3].height()-m[0].height())/2}).width(m[3].width());
                    m[1].width(m[0].width());
                }
                
                if(m[0].is(':visible')) setSize();
                else m[0].unbind('mouseenter').bind('mouseenter',function(){
                        setSize();
                        $(this).unbind('mouseenter');
                    });
            })
        },
        start: function(id){
            
            var m = m_in(id),
                m_p = [m[3].width(),m[3].height()],
                m_c = [m[0].width(),m[0].height()],
                t = 0,
                l = m[1].length,
                n = '';
                
            m[0].css({paddingTop:(m_p[1]-m_c[1])/2}).width(m_p[0]);
            
            m[2].addClass('metro_size');
            m[1].width(m_c[0]).eq(0).width(m_c[0]).find('.metro').removeClass('metro_size');
            
            function s_m(obj,iti){
                setTimeout(function(){
                    $(obj).animate({opacity:1,left: 0},300);
                },iti);
            }
            
            $.each(m[1],function(){
                $(this).css({paddingBottom:((m[0].height()-$(this).height())/2)+'px'});
            });
            
            $.each($('.lay',m[2]),function(){
                s_m(this,t += 100);
            });
            
            if(l > 1){
                for(i=0;i<l;i++) n += '<li'+(i==0 ? ' class="active"' : '')+' onclick="$.metro(\'move\','+i+(id ? ",'"+id+"'" : '')+')"><b></b><span></span></li>';
                m[0].append('<ul class="metro_nav">'+n+'</ul>');
                
                var m_nav = m[0].find('.metro_nav');
                m_nav.css({marginLeft: '-'+(m_nav.width() / 2)+'px'});
                
                m[0].hammer().on('swipe',function(event){
                    event.preventDefault();  methods.moveDirection(event.direction,id);
                })
            }
        },
        moveDirection: function(direct,id){
            var m = m_in(id),
                n = $('.metro_nav',m[0]),
                i = $('li.active',n).index();
            
            i = direct == 'right' ? (i-1) : (i+1);
            i = i > n.lenght ? n.lenght : (i < 0 ? 0 : i);
            
            methods.move(i,id);
        },
        move: function(q,id){
            var m = m_in(id),
                m_p = [m[3].width(),m[3].height()],
                m_c = [m[0].width(),m[0].height()];
                
            if(q <= m[1].length-1){
                m[1].eq(0).css({marginLeft:'-'+(q * m_c[0])+'px'});
                
                m[2].addClass('metro_size');
                m[1].eq(q).width(m_c[0]).find('.metro').removeClass('metro_size');
                
                m[0].find('.metro_nav li').removeClass('active').eq(q).addClass('active');
            }
        }
    };
    
    $.fn.metro = function( method ) {
    
        if ( methods[method] ) {
          return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.ready.apply( this, arguments );
        } else {
          return methods.ready.apply( this );
        }   
    
    };
    
    $.metro = function( method ) {
    
        if ( methods[method] ) {
          return methods[ method ].apply( false,Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
          return methods.ready.apply( false, arguments );
        } else {
          return methods.ready();
        }   
    
    };
  

})(jQuery);