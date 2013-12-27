/**
 * @desktop
 * @author korner
 * @copyright SL-SYSTEM 2012
 */

var DSS = (function($, window, document, undefined) {
    var dp = {x:0,y:0};
    var touchMoveObj = false;
    
    return {
        new_obj: function(o,n){
            $('.icon').removeClass('active new');
            
            if(o.type == 'i')
            var $hgi = $([
                '<div class="icon aero_radius aero_shadow ',(n && 'new'),'" name="',o.name,'">',
                    '<div class="con preload">',
                        '<img src="',(o.ico_img ? '/modules/'+o.name+'/ico.png' : pathIco),'" class="img t_animate" />',
                        '<div class="tit"><span>',(o.title ? o.title.substr(0,7) : 'default'),'</span></div>',
                    '</div>',
                '</div>'
                ].join(''));
            else
            var $hgi = $([
                '<div class="widget ',(n && 'new'),'" name="',o.name,'">',
                    '<div class="con">',
                    '</div>',
                '</div>'
                ].join(''));
            
            n && (o.x = o.x - ($hgi.outerWidth()/2),o.y = o.y - ($hgi.outerHeight()/2));
            
            var css = {
                left: o.x ,
                top: o.y
            };
            
            $hgi.on('mouseup touchstart',function(event){
                event.preventDefault();
                
                $(this).addClass('active');
                
                touchMoveObj = [$(this),false];
            })
            
            $hgi.on('touchmove',function(e){
                if(touchMoveObj){
                    var e = e.originalEvent.changedTouches[0],_this = touchMoveObj[0],w,h;
                    
                    w = _this.width() / 2;
                    h = _this.height() / 2;
            
                    _this.css({left:(e.pageX-w),top:(e.pageY-h)});
                    
                    touchMoveObj[1] = 1;
                }
            })
            
            
            $hgi.on('touchend', function(e){
                
                if(!touchMoveObj || !touchMoveObj[1]) return;
                
                var e = e.originalEvent.changedTouches[0],_this = $(this),w,h,ig;
                
                w = _this.width() / 2;
                h = _this.height() / 2;
                
                DSS.dragObj($(this),(e.pageX-w),(e.pageY-h));
                
                touchMoveObj = false;
            });
            
            $hgi.hammer().on('hold doubletap',function(event){
                var o = $(this).data('info');
                
                if(event.type == 'hold'){
                    $(this).sl('menu',{
                        'Удалить':function(){
                            DSS.util(o.name,o.type)
                        }
                    });
                }
                else if(event.type == 'doubletap'){
                    if($(this).hasClass('icon')) $.sl('shell',{name:o.name});
                }
            })
            
            $hgi.hide().css(css).data('info',o).appendTo('#wrap').fadeIn(300);
            
            if(o.type == 'i') $hgi.find('.img').load(function(){
                $(this).addClass('icoload').parents('.con').removeClass('preload');
            });
            else $hgi.find('.con').sl('load','/ajax/'+o.name+'/widget');
            
            return $hgi;
        },
        create: function(o){
            o = jQuery.extend({
                x:dp.x,
                y:dp.y,
                name:'default',
                type: 'i'
            }, o);
            
            $.sl('load','/ajax/fn/infomod/'+o.name,{dataType:'json',mode:'quiet'},function(j){
                 
                if(typeof j == 'object'){
                    j['x'] = o.x;
                    j['y'] = o.y;
                    j['name'] = o.name;
                    j['type'] = o.type;
                    
                    var is_ico = $('div.icon[name='+j.name+']');
                    
                    if(is_ico.length){
                        is_ico.addClass('new');
                        return;
                    }
                    
                    DSS.new_obj(j,1);
                    $.sl('load','/ajax/desktop/update/'+o.name+'/'+j.x+'/'+j.y+'/'+j.title+'/'+o.type+'/'+(j.ico_img ? 1 : 0),{mode:'hide'});
                }
            });
        },
        dragObj: function(_this,x,y){
            x = (Math.round(x/20)*20);
            y = (Math.round(y/20)*20);
    
            _this.animate({left:x+'px',top:y+'px'},300);
            
            var ig = _this.data('info');
            
            $.sl('load','/ajax/desktop/update/'+ig.name+'/'+x+'/'+y+'/'+ig.title+'/'+ig.type+'/'+ig.ico_img,{mode:'hide'});
        },
        loadDe:function(){
            setTimeout(function(){
                $.sl('load','/ajax/desktop/get',{dataType:'json'},function(i){
                    if(i.error) $.sl('info',i.error);
                    else{
                        $.each(i,function(name,o){
                            o['name'] = name;
                            DSS.new_obj(o);
                        });
                    }
                })
            },1000);
        },
        init: function() {
                
            $(document).on('mousemove',function(e){
                dp.x = e.pageX;
                dp.y = e.pageY;
            });
            
            //Загрузка фона
            
            im = new Image(); 
            im.onload = function(){
                setTimeout(function(){
                    $('#bg').addClass('bgload');
                },1000)
            }
            im.onerror = function(){
                $('#bg').addClass('bgload');
            }
            im.src = '/upload/admin_bg/default.jpg';
            
            //Загрузка рабочего стола
            
            imb = new Image(); 
            imb.onload = function(){
                DSS.loadDe();
            }
            imb.onerror = function(){
                DSS.loadDe();
            }
            imb.src = '/upload/admin_bg/blur.jpg'; 
            
            
            $(document).bind('contextmenu', function(ev) {
                if (!$(ev.target).closest(['a', 'input', 'select', 'textarea']).length) return false;
                else return true;
            }).on('mousedown', function() {
                $('.icon').removeClass('active new')
            });
            
            $('[tip]').sl('tip');
            
            $.sl('top_panel',{fun:"$(this).sl('shell',{name:'admin_menu'})",login:dslogin});
            
            $('div.icon,div.widget').live('mouseenter', function() {
                $(this).die('mouseenter').draggable({
                    containment: 'parent',
                    stop:function(event,ui){
                        DSS.dragObj($(this),ui.offset.left,ui.offset.top);
                    }
                });
            })
            
            $(window).resize(function() {
                DSS.setSize();
            })
        },
        setSize: function(){
            $('#win_bg,#wrap,#bg').css({width:window.innerWidth,height:window.innerHeight});
        },
        util: function(name,t){
            $('div.'+(t == 'i' ? 'icon' : 'widget')+'[name='+name+']').fadeOut(function(){
                $(this).remove();
                $.sl('load','/ajax/desktop/delete/'+name,{mode:'hide'});
            })
            
        }
    };
})(jQuery, this, this.document);

jQuery(document).ready(function() {
    DSS.init();
});