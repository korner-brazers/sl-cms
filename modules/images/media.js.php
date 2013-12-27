<?
header("Content-type: application/x-javascript");
?>
var init_dropbox    = true;
var init_dropbox_id = 0;
var init_dropbox_tbl = 0;
var init_dropbox_admin = false;
var init_dropbox_select_id = 0;
var init_dropbox_add_menu = false;

var image_box_prew,dropbox,message,box_conteiner_prew;

$('#image_box').live('mouseenter',function(){
    if(init_dropbox) return false;
    else init_dropbox = true;
    
    dropbox = $('#dropbox',this);
    message = $('.message', dropbox);
    image_box_prew     = $('#image_box_prew');
    box_conteiner_prew = $('.box_conteiner', image_box_prew);

    dropbox.filedrop({
		paramname:'pic',
		maxfiles: 5,
    	maxfilesize: 2,
		url: '/ajax/images/upload_img/'+init_dropbox_id+'/'+init_dropbox_tbl,
		
		uploadFinished:function(i,file,response){
		  
          if(response.error){
              $.sl('info',response.error);
              $.data(file).fadeOut(function(){ $(this).remove() });
          } 
          else $.data(file).addClass('done').attr('id',response.id).find('.view-img').attr('rel',response.src_big);
		},
		
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					$.sl('info','Too many files! Please select 5 at most! (configurable)');
					break;
				case 'FileTooLarge':
					$.sl('info',file.name+' is too large! Please upload files up to 2mb (configurable).');
					break;
				default:
					break;
			}
		},

		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				$.sl('info','Only images are allowed!');
				return false;
			}
		},
		
		uploadStarted:function(i, file, len){
			createImage(file);
		},
		
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').css('width',progress+'%');
		}
    	 
	});
});

$('.image_box_close_prew').live('click',function(){
    image_box_prew.animate({top:'-100%',opacity:0},600,function(){ $(this).find('.box_conteiner').html('') });
});

$('#image_box .preview .edit_ico').live('click',function(){
    var _this  = $(this).parents('li'),
        parent = $(this).parents('ul'),
        id     = _this.attr('id');
    
    init_dropbox_select_id = id;
    
    var menu_admin = {
        'Оригинал':function(){
            $.sl('_promt',{input:[{value:'<img src=\'/ajax/images/id/'+id+'/'+init_dropbox_tbl+'/original\' class=\'img\' />'}]});
        },
        'По размеру':function(){
            $.sl('_promt',{input:[{holder:'width px',regex:'[^0-9]'},{holder:'height px',regex:'[^0-9]'}],btn:{
                'Подогнать':function(wn,data){
                    $.sl('_promt',{input:[{value:'<img src=\'/ajax/images/id/'+id+'/'+init_dropbox_tbl+'/size/'+data[0].value+'/'+data[1].value+'\' class=\'img\' />'}]});
                }
            }});
        },
        'Прикрепить':function(){
             $.sl('_promt',{input:[{holder:'ID',regex:'[^0-9]'}],btn:{
                'Прикрепить':function(wn,data){
                    $.sl('load','/ajax/<?=$_GET['module']?>/attach/'+id+'/'+data[0].value+'/'+init_dropbox_tbl);
                }
            }});
        },
        'Сделать фоном админ панели':function(){
            $.sl('load','/ajax/<?=$_GET['module']?>/create_bg_admin/'+id+'/'+init_dropbox_tbl,function(){
                $.sl('_confirm','Фон админ панели был обновлен, чтоб действия вступили в силу необходимо перезагрузить страницу',function(){
                    window.location = document.location.pathname;
                })
            });
        }
    };
        
    var menu = {
        'Удалить':function(){
            $.sl('load','/ajax/images/delete/'+id+'/'+init_dropbox_tbl,function(){
                _this.remove();
            });
        }
    };
    
    if(init_dropbox_admin) menu = $.extend(menu,menu_admin);
    if(init_dropbox_add_menu) menu = $.extend(menu,init_dropbox_add_menu);
    
    $(this).sl('menu',menu,{zIndex:600});
});

$('#image_box .preview .view-img').live('click',function(){
    var rel = $(this).attr('rel');
    if(!rel) return;
    
    image_box_prew.animate({top:0,opacity:1},300,function(){
        box_conteiner_prew.html('<img src="'+rel+'" />').tinyscrollbar_remove();
        box_conteiner_prew.find('img').hide().load(function(){
            var w,h,ww = $(window).width(), wh = $(window).height();
            
            $(this).css({maxWidth:ww});
            
            w = $(this).width(),h = $(this).height();
            
            w < ww && $(this).css({marginLeft:(ww-w)/2});
            h < wh && $(this).css({marginTop:(wh-h)/2});
            
            $(this).fadeIn();
            
            box_conteiner_prew.tinyscrollbar();
        })
    });
});


function createImage(file){
    
   	var template = '<li class="preview">'+
                    '<div class="view-img">'+
                        '<img class="img" />'+
                    '</div>'+
        			'<div class="progressHolder">'+
        				'<div class="progress"></div>'+
        			'</div>'+
                    '<div class="edit_ico"></div>'+
                '</li>';
                
	var preview = $(template), 
		image   = $('img', preview);
		
	var reader = new FileReader();
	
	image.width = 100;
	image.height = 100;
	
	reader.onload = function(e){

		image.hide().attr('src',e.target.result).load(function(){
            $(this).css({marginLeft:(preview.width() - $(this).width())/2,marginTop:(preview.height() - $(this).height())/2}).fadeIn();
        })
        
	};
	
	reader.readAsDataURL(file);
	
    dropbox.after(preview)
    
    $.sl('update_scroll');
	
	$.data(file,preview);
}

function showMessage(msg){
	message.html(msg);
}   
