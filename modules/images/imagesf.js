/*
 * Default text - jQuery plugin for html5 dragging files from desktop to browser
 *
 * Author: Weixi Yen
 *
 * Email: [Firstname][Lastname]@gmail.com
 * 
 * Copyright (c) 2010 Resopollution
 * 
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.github.com/weixiyen/jquery-filedrop
 *
 * Version:  0.1.0
 *
 * Features:
 *      Allows sending of extra parameters with file.
 *      Works with Firefox 3.6+
 *      Future-compliant with HTML5 spec (will work with Webkit browsers and IE9)
 * Usage:
 * 	See README at project homepage
 *
 */
(function($){

	jQuery.event.props.push("dataTransfer");
	var opts = {},
		default_opts = {
			url: '',
			refresh: 1000,
			paramname: 'userfile',
			maxfiles: 25,
			maxfilesize: 1, // MBs
			data: {},
			drop: empty,
			dragEnter: empty,
			dragOver: empty,
			dragLeave: empty,
			docEnter: empty,
			docOver: empty,
			docLeave: empty,
			beforeEach: empty,
			afterAll: empty,
			rename: empty,
			error: function(err, file, i){alert(err);},
			uploadStarted: empty,
			uploadFinished: empty,
			progressUpdated: empty,
			speedUpdated: empty
		},
		errors = ["BrowserNotSupported", "TooManyFiles", "FileTooLarge"],
		doc_leave_timer,
		stop_loop = false,
		files_count = 0,
		files;

	$.fn.filedrop = function(options) {
		opts = $.extend( {}, default_opts, options );
		
		this.bind('drop', drop).bind('dragenter', dragEnter).bind('dragover', dragOver).bind('dragleave', dragLeave);
		$(document).bind('drop', docDrop).bind('dragenter', docEnter).bind('dragover', docOver).bind('dragleave', docLeave);
	};
     
	function drop(e) {
		opts.drop(e);
		files = e.dataTransfer.files;
		if (files === null || files === undefined) {
			opts.error(errors[0]);
			return false;
		}
		
		files_count = files.length;
		upload();
		e.preventDefault();
		return false;
	}
	
	function getBuilder(filename, filedata, boundary) {
		var dashdash = '--',
			crlf = '\r\n',
			builder = '';

		$.each(opts.data, function(i, val) {
	    	if (typeof val === 'function') val = val();
			builder += dashdash;
			builder += boundary;
			builder += crlf;
			builder += 'Content-Disposition: form-data; name="'+i+'"';
			builder += crlf;
			builder += crlf;
			builder += val;
			builder += crlf;
		});
		
		builder += dashdash;
		builder += boundary;
		builder += crlf;
		builder += 'Content-Disposition: form-data; name="'+opts.paramname+'"';
		builder += '; filename="' + filename + '"';
		builder += crlf;
		
		builder += 'Content-Type: application/octet-stream';
		builder += crlf;
		builder += crlf; 
		
		builder += filedata;
		builder += crlf;
        
		builder += dashdash;
		builder += boundary;
		builder += dashdash;
		builder += crlf;
		return builder;
	}

	function progress(e) {
		if (e.lengthComputable) {
			var percentage = Math.round((e.loaded * 100) / e.total);
			if (this.currentProgress != percentage) {
				
				this.currentProgress = percentage;
				opts.progressUpdated(this.index, this.file, this.currentProgress);
				
				var elapsed = new Date().getTime();
				var diffTime = elapsed - this.currentStart;
				if (diffTime >= opts.refresh) {
					var diffData = e.loaded - this.startData;
					var speed = diffData / diffTime; // KB per second
					opts.speedUpdated(this.index, this.file, speed);
					this.startData = e.loaded;
					this.currentStart = elapsed;
				}
			}
		}
	}
    
    
    
	function upload() {
		stop_loop = false;
		if (!files) {
			opts.error(errors[0]);
			return false;
		}
		var filesDone = 0,
			filesRejected = 0;
		
		if (files_count > opts.maxfiles) {
		    opts.error(errors[1]);
		    return false;
		}

		for (var i=0; i<files_count; i++) {
			if (stop_loop) return false;
			try {
				if (beforeEach(files[i]) != false) {
					if (i === files_count) return;
					var reader = new FileReader(),
						max_file_size = 1048576 * opts.maxfilesize;
						
					reader.index = i;
					if (files[i].size > max_file_size) {
						opts.error(errors[2], files[i], i);
						filesRejected++;
						continue;
					}
					
					reader.onloadend = send;
					reader.readAsBinaryString(files[i]);
				} else {
					filesRejected++;
				}
			} catch(err) {
				opts.error(errors[0]);
				return false;
			}
		}
	    
		function send(e) {
			// Sometimes the index is not attached to the
			// event object. Find it by size. Hack for sure.
			if (e.target.index == undefined) {
				e.target.index = getIndexBySize(e.total);
			}
			
			var xhr = new XMLHttpRequest(),
				upload = xhr.upload,
				file = files[e.target.index],
				index = e.target.index,
				start_time = new Date().getTime(),
				boundary = '------multipartformboundary' + (new Date).getTime(),
				builder;
				
			newName = rename(file.name);
			if (typeof newName === "string") {
				builder = getBuilder(newName, e.target.result, boundary);
			} else {
				builder = getBuilder(file.name, e.target.result, boundary);
			}
			
			upload.index = index;
			upload.file = file;
			upload.downloadStartTime = start_time;
			upload.currentStart = start_time;
			upload.currentProgress = 0;
			upload.startData = 0;
			upload.addEventListener("progress", progress, false);
			
			xhr.open("POST", opts.url, true);
			xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' 
			    + boundary);
			    
			xhr.sendAsBinary(builder);  
			
			opts.uploadStarted(index, file, files_count);  
			
			xhr.onload = function() { 
			    if (xhr.responseText) {
				var now = new Date().getTime(),
				    timeDiff = now - start_time,
				    result = opts.uploadFinished(index, file, jQuery.parseJSON(xhr.responseText), timeDiff);
					filesDone++;
					if (filesDone == files_count - filesRejected) {
						afterAll();
					}
			    if (result === false) stop_loop = true;
			    }
			};
		}
	}
    
	function getIndexBySize(size) {
		for (var i=0; i < files_count; i++) {
			if (files[i].size == size) {
				return i;
			}
		}
		
		return undefined;
	}
    
	function rename(name) {
		return opts.rename(name);
	}
	
	function beforeEach(file) {
		return opts.beforeEach(file);
	}
	
	function afterAll() {
		return opts.afterAll();
	}
	
	function dragEnter(e) {
		clearTimeout(doc_leave_timer);
		e.preventDefault();
		opts.dragEnter(e);
	}
	
	function dragOver(e) {
		clearTimeout(doc_leave_timer);
		e.preventDefault();
		opts.docOver(e);
		opts.dragOver(e);
	}
	 
	function dragLeave(e) {
		clearTimeout(doc_leave_timer);
		opts.dragLeave(e);
		e.stopPropagation();
	}
	 
	function docDrop(e) {
		e.preventDefault();
		opts.docLeave(e);
		return false;
	}
	 
	function docEnter(e) {
		clearTimeout(doc_leave_timer);
		e.preventDefault();
		opts.docEnter(e);
		return false;
	}
	 
	function docOver(e) {
		clearTimeout(doc_leave_timer);
		e.preventDefault();
		opts.docOver(e);
		return false;
	}
	 
	function docLeave(e) {
		doc_leave_timer = setTimeout(function(){
			opts.docLeave(e);
		}, 200);
	}
	 
	function empty(){}
	
	try {
		if (XMLHttpRequest.prototype.sendAsBinary) return;
		XMLHttpRequest.prototype.sendAsBinary = function(datastr) {
		    function byteValue(x) {
		        return x.charCodeAt(0) & 0xff;
		    }
		    var ords = Array.prototype.map.call(datastr, byteValue);
		    var ui8a = new Uint8Array(ords);
		    this.send(ui8a.buffer);
		}
	} catch(e) {}
     
})(jQuery);

	var dropbox = $('#dropbox'),
        message = $('.message', dropbox),
        box_list = $('#box_list');
	console.log(dropbox);
                
                dropbox.filedrop({
            		paramname:'pic',
            		maxfiles: 5,
                	maxfilesize: 2,
            		url: '/ajax/profile/upload_img/'+id,
            		
            		uploadFinished:function(i,file,response){
            		  
                      if(response.error){
                          $.sl('info',response.error);
                          $.data(file).fadeOut(function(){ $(this).remove() });
                      } 
                      else $.data(file).addClass('done').attr('name',response.name).find('.view-img').attr('rel',response.src_big);
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
            			$.data(file).find('.progress').width(progress);
            		}
                	 
            	});
                
            
    
    
    $('.image_box_close_prew').live('click',function(){
        image_box_prew.animate({top:'-100%',opacity:0},600,function(){ $(this).find('.box_conteiner').html('') });
    });
    
    $('#image_box .preview .edit_ico').live('click',function(){
        var _this  = $(this).parents('li'),
            parent = $(this).parents('ul');
        
        $(this).sl('menu',{
            'Сделать иконкой':function(){
                if(_this.hasClass('ico') || _this.hasClass('poster')) {
                    $.sl('info','Это изображение уже установлено как иконка или постер')
                    return;
                }
                $.sl('load','/ajax/profile/createImg/'+lastId+'/ico',{dataType:'json',data:{name:_this.attr('name')}},function(j){
                    $('.ico',parent).remove();
                    _this.addClass('ico').attr('name',j.name).find('img').attr('src',j.src).load(function(){
                        $(this).css({marginLeft:(_this.width() - $(this).width())/2,marginTop:(_this.height() - $(this).height())/2});
                    });
                    _this.find('.view-img').attr('rel',j.src);
                });
            },
            'Сделать как постер':function(){
                if(_this.hasClass('ico') || _this.hasClass('poster')) {
                    $.sl('info','Это изображение уже установлено как иконка или постер')
                    return;
                }
                $.sl('load','/ajax/profile/createImg/'+lastId+'/poster',{dataType:'json',data:{name:_this.attr('name')}},function(j){
                    $('.poster',parent).remove();
                    _this.addClass('poster').attr('name',j.name);
                    $('.view-img',_this).attr('rel',j.src);
                });
            },
            'Удалить':function(){
                $.sl('load','/ajax/profile/deleteImg/'+lastId+'/'+(_this.hasClass('ico') ? 'ico' : _this.hasClass('poster') ? 'poster' : ''),{data:{name:_this.attr('name')}},function(){
                    _this.remove();
                });
            }
        },{zIndex:225});
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


function bytesToSize(bytes, precision){  
    var kilobyte = 1024;
    var megabyte = kilobyte * 1024;
    var gigabyte = megabyte * 1024;
    var terabyte = gigabyte * 1024;
   
    if ((bytes >= 0) && (bytes < kilobyte)) {
        return bytes + ' B';
 
    } else if ((bytes >= kilobyte) && (bytes < megabyte)) {
        return (bytes / kilobyte).toFixed(precision) + ' KB';
 
    } else if ((bytes >= megabyte) && (bytes < gigabyte)) {
        return (bytes / megabyte).toFixed(precision) + ' MB';
 
    } else if ((bytes >= gigabyte) && (bytes < terabyte)) {
        return (bytes / gigabyte).toFixed(precision) + ' GB';
 
    } else if (bytes >= terabyte) {
        return (bytes / terabyte).toFixed(precision) + ' TB';
 
    } else {
        return bytes + ' B';
    }
}