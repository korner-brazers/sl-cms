<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class upload{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function fn($o = []){
        if($this->modInfo[5]) return;
        
        return $this->modInfo[0].'_open('.str_replace('"',"'",json_encode($o)).');';
    }
    private function get_extension($file_name){
    	$ext = explode('.', $file_name);
    	$ext = array_pop($ext);
    	return strtolower($ext);
    }
    function upload_file($dir = false){
        if($this->sl->fn->check_ac('user')) return;
        
        $op = $this->sl->settings->get(['files','dir','paramname'],$this->modInfo[0]);
        
        $allowed_ext = explode(',',$op['files']);
        
        $op['dir'] = $dir ? urldecode($dir) : $op['dir'];
        
        $upload_dir = SL_UPLOAD.DIR_SEP.$op['dir'].DIR_SEP;
        
        if(!is_dir($upload_dir)) $this->sl->fn->info('Неверно указана директория для загрузки!');
        
        if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
       	    $this->sl->fn->info('Ошибка! Неправильный метод HTTP!');
        }
        
        $il = $this->sl->fn->replase($op['paramname']);
        
        if(empty($il)) $this->sl->fn->info('Не указан (paramname)');
        
        if(array_key_exists($il,$_FILES) && $_FILES[$il]['error'] == 0 ){
        	
        	$file = $_FILES[$il];
            
            $ex = $this->get_extension($file['name']);
            
        	if(!in_array($ex,$allowed_ext)){
        		$this->sl->fn->info('Только ('.implode(',',$allowed_ext).') файлов разрешено!');
        	}
            
            $md5 = md5(time().$_FILES[$il]['name']);
            $md5_ex = $md5.'.'.$ex;
            
       	    if(move_uploaded_file($file['tmp_name'], $upload_dir.$md5_ex)){
                
                return [
                    'secuses'=>true,
                    'size'=>$this->sl->fn->filesize($upload_dir.$md5_ex,true),
                    'name'=>$file['name'],
                    'name_full'=>$md5_ex,
                    'ex'=>$ex,
                    'path'=>'/upload/'.$op['dir'].'/'.$md5_ex
                ];
        	}
        	
        }
        
        $upload_errors = array(
            UPLOAD_ERR_OK        => "No errors.",
            UPLOAD_ERR_INI_SIZE    => "Larger than upload_max_filesize.",
            UPLOAD_ERR_FORM_SIZE    => "Larger than form MAX_FILE_SIZE.",
            UPLOAD_ERR_PARTIAL    => "Partial upload.",
            UPLOAD_ERR_NO_FILE        => "No file.",
            UPLOAD_ERR_NO_TMP_DIR    => "No temporary directory.",
            UPLOAD_ERR_CANT_WRITE    => "Can't write to disk.",
            UPLOAD_ERR_EXTENSION     => "File upload stopped by extension.",
            UPLOAD_ERR_EMPTY        => "File is empty." // add this to avoid an offset
        ); 
        
        $this->sl->fn->info('Что-то пошло не так с вашей загрузкой! ('.$upload_errors[$_FILES[$il]['error']].')');
    }
    function show(){
        if($this->sl->fn->check_ac('admin')) return;
        
        $op = $this->sl->settings->get(['maxfilesize','url','paramname'],$this->modInfo[0]);
        
        $this->sl->settings->set_default([
            'paramname'=>['value'=>'file','descr'=>'Название параметра POST запроса','status'=>1],
            'maxfilesize'=>['value'=>'20','descr'=>'Максимальный размер файла в Mb','status'=>1],
            'files'=>['value'=>'txt,zip,jpg,png','descr'=>'Расширение файлов допустимых для загрузки','status'=>1],
            'dir'=>['value'=>'files','descr'=>'Директория для загрузки','status'=>1],
        ],$this->modInfo[0]);
        
        $fnop = [
            'call'=>$this->modInfo[0].'_result_info',
            'paramname'=>$op['paramname'],
            'maxfilesize'=>intval($op['maxfilesize']),
            'url'=>($op['url'] ? $op['url'] : '/ajax/'.$this->modInfo[0].'/upload_file/')
        ];
        
        $html = '
            <div class="'.$this->modInfo[0].'_content win_h_size" minus="30">
                <div class="up_btn" onclick="'.$this->fn($fnop).'">Кликнуть для загрузки</div>
                <div class="up_info">
                    <div class="t_p_20">
                    <h3 class="smooth">Информация о файле</h3>
                    <div id="upload_result">
                    <p>Для работы модуля проверьте что его компоненты подключены через модуль (<b class="t_color_w">plugins</b>)</p>
                    </div>
                    </div>
                </div>
                <div class="up_ec"></div>
            </div>
        ';
        
        return $this->sl->scin->slide([
            'Загрузка'=>$html,
            'Настройки'=>$this->sl->settings->show($this->modInfo[0])
        ]).$this->sl->scin->cache_css(__DIR__);
    }
}
?>