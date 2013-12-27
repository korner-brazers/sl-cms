<?
header("Content-type: application/x-javascript");
?>
function <?=$_GET['module']?>_open(o,callback){
    var o = jQuery.extend({
        paramname: 'file',
        maxfilesize: 20,
        url: '/ajax/<?=$_GET['module']?>/upload_file',
        call:false,
        addparam: false
    }, o);
    
    o.url = o.dir ? o.url + '/' + escape(o.dir) : o.url;
    
    var html = [
        '<div class="t_p_r t_p_10">',
            '<div class="<?=$_GET['module']?>_conteiner" id="<?=$_GET['module']?>_drop">',
                '<div class="up_progress"><div></div></div>',
                '<div class="up_pr">100%</div>',
            '</div>',
        '</div>'
    ].join(' ');
    
    $.sl('window',{
        data:html,
        w: 300,
        h: 170,
        bg: false,
        drag: true,
        title: 'Переташите файл',
        name: 'win_<?=$_GET['module']?>'
    },function(){
        
        var drop = $('#<?=$_GET['module']?>_drop');
            
        drop.filedrop({
    		paramname: o.paramname,
    		maxfiles: 1,
        	maxfilesize: o.maxfilesize,
    		url: o.url,
    		
    		uploadFinished:function(i,file,response){
  		        drop.removeClass('load');
                
                if(response.error) $.sl('info',response.error);
                else{
                    o.call && window[o.call](response,'win_<?=$_GET['module']?>',o.addparam);
                    callback && callback(response,'win_<?=$_GET['module']?>',o.addparam);
                }
    		},
    		
        	error: function(err, file) {
    			switch(err) {
    				case 'BrowserNotSupported':
    					$('.zip_box').html('Ваш браузер не поддерживает HTML5 добавления файла!');
    					break;
    				case 'TooManyFiles':
    					$.sl('info','Слишком много файлов! Пожалуйста, выберите 1 не более!');
    					break;
    				case 'FileTooLarge':
    					$.sl('info',file.name+' Слишком большое! Пожалуйста, загрузите файлы размером до 2 Мб.');
    					break;
    				default:
    					break;
    			}
    		},
    		
    		uploadStarted:function(i, file, len){
                drop.addClass('load');
    		},
        		
    		progressUpdated: function(i, file, progress) {
    			drop.find('.up_progress div').width(progress+'%');
                drop.find('.up_pr').text(progress+'%');
    		}
        	 
    	});
        
    })
}
function <?=$_GET['module']?>_result_info(o,obj){
    var html = [
        '<ul class="t_ul">',
            '<li><b>Название:</b>',o.name,'</li>',
            '<li><b>Полное название:</b>',o.name_full,'</li>',
            '<li><b>Размер:</b>',o.size,'</li>',
            '<li><b>Тип:</b>',o.ex,'</li>',
            '<li><b>Путь:</b>',o.path,'</li>',
        '</ul>'
    ].join(' ');
    
    $(obj || '#upload_result').html(html);
}