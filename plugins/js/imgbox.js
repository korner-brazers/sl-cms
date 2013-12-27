jQuery.fn.imgbox = function(o){
    var o = jQuery.extend({
        zIndex: 990,
        selector: 'rel'
    },o),c = 0,i = 0,s,_this,box,box_conteiner;
    
    if(!$('#image_box_prew').length){
        $box = $([
            '<div id="image_box_prew" class="image_box t_p_f t_left t_width t_height">',
                '<div class="t_p_r"><div class="close_box image_box_close_prew t_animate t_p_a t_left t_top t_width" style="z-index: 40"></div></div>',
                '<div style="height: ',(window.innerHeight-45),'px; margin-top: 45px;" class="boxhei"><div class="box_conteiner t_over"></div></div>',
            '</div>'
        ].join(''));
        
        $box_conteiner = $('.box_conteiner',$box);
        
        $box.appendTo('body');
        
        $('#image_box_prew .image_box_close_prew').live('click',function(){
            $box.fadeOut(function(){ $box_conteiner.html(''); $('body').css({overflow:'auto'}); });
        });
        
    }
    
    function showBox(src){
        $box.addClass('loading');
        $box_conteiner.empty().tinyscrollbar_remove();
        
        var i = new Image(); 
        
        i.onload = function(){
            $box_conteiner.html('<img src="'+src+'" />');
            
            var img = $('img',$box_conteiner).hide(),w,h,ww = window.innerWidth, wh = (window.innerHeight-45);
            
            img.css({maxWidth:ww});
            
            w = i.width,h = i.height;
            
            w < ww && img.css({marginLeft:(ww-w)/2});
            h < wh && img.css({marginTop:(wh-h)/2});
            
            img.fadeIn();
            
            $box_conteiner.tinyscrollbar();
            $box.removeClass('loading');
        }
        i.onerror = function(){
            $box_conteiner.html('<div class="error">Не удалось загрузить изображение</div>').tinyscrollbar();
            $box.removeClass('loading');
        }
        i.src = src;
    }
    
    $(this).click(function(){
        _this = $(this);
            s = _this.attr(o.selector);
            i = _this.index();
            c = _this.length
        
        if(s){
            $('body').css({overflow:'hidden'});
            
            $('.boxhei',$box).height(window.innerHeight-45);
            
            $box.fadeIn(function(){
                showBox(s);
            });
        }
        
    })
    
    return this;
};