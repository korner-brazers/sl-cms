<?
/**
 * @online_booking
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class bridge{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->tbl_name = $moduleInfo[0];
        $this->tbl_name_list = $moduleInfo[0].'_list';
    }
    function check_login(){
        if($this->sl->fn->check_ac('root')) return;
        
        $user = $this->sl->fn->server('conect/check_login/'.urlencode($_POST['login']).'/'.md5($_POST['pass']));
        
        if($user['success']){
            $this->sl->auth->cookie("bridge_l", $_POST['login'], 360);
            $this->sl->auth->cookie("bridge_p", $_POST['pass'], 360);
        }
    }
    function show($page = 1,$like = false){
        if($this->sl->fn->check_ac('root')) return;
        
        return $this->sl->scin->cache_js(__DIR__)."
        <script>
        var bridge_l = bridge_l ? bridge_l : '".$_COOKIE['bridge_l']."';
        var bridge_p = bridge_p ? bridge_p : '".$_COOKIE['bridge_p']."';
        setTimeout(function(){{$this->modInfo[0]}_login();},500);
        </script>".$this->sl->scin->cache_css(__DIR__);
    }
    function step($p = 1,$id = 0,$ver = 0){
        if($this->sl->fn->check_ac('root')) return;
        
        $login = urlencode($_POST['login']);
        $pass  = md5($_POST['pass']);
        $id    = intval($id);
        $str   = '';
        
        if($p == 1){
            $apps = $this->sl->fn->server('conect/my_module/'.$login.'/'.$pass);
            
            if(count($apps) == 0) $str .= '<div class="t_left win_h_size scrollbarInit bridge_apps"><p class="t_p_20">Вы еше не публиковали модули в маркет, для того чтоб опубликовать модуль перейди на сайт <a href="http://sl-cms.com" target="_blank" class="t_color_w">sl-cms.com</a></p></div>';
            else{
                $str .= '<div class="t_left win_h_size scrollbarInit bridge_apps"><ul class="bridge_apps_list">';
                foreach($apps as $row){
                    $str .= '<li ver="'.$row['ver'].'" id="'.$row['id'].'">
                        <div>
                            <div class="ico t_left"><img src="http://sl-cms.com/upload/market/ico/'.$row['ico'].'" /></div>
                            <div class="t_left con">
                                <span class="title">'.$this->sl->fn->substr(strip_tags($row['name']),0,20).'</span>
                                <span class="descr">'.$this->sl->fn->substr(strip_tags($row['descr']),0,24).'</span>
                            </div>
                        </div>
                    </li>';
                }
                $str .= '</ul></div>';
            }
        }
        elseif($p == 2){
            $str .= '<div class="t_left win_h_size scrollbarInit bridge_files">
                        <div class="t_p_a t_top t_left t_width">'.$this->sl->scin->btn_group(['Обновить'=>['/ajax/'.$this->modInfo[0].'/dir_show','quiet'],]).'</div>
                        <div id="bridge_tree"></div>
                    </div>
                    <div class="t_left win_h_size scrollbarInit bridge_files color_d">
                        <div class="t_p_a t_top t_left t_width">'.$this->sl->scin->btn_group(['Очистить архив'=>['/ajax/'.$this->modInfo[0].'/clear_zip','quiet',"$('#bridge_tree_zip').html(''); $.sl('update_scroll'); $('#{$this->modInfo[0]}_next_btn').fadeOut()"]]).'</div>
                        <div id="bridge_tree_zip"></div>
                    </div>
                    <script>$(\'#bridge_tree\').fileTree({ root: \'/\' });</script>';
                    
            $this->clear_zip();
        }
        elseif($p == 3) $str .= '<div class="win_h_size t_p_r"></div>';
        elseif($p == 4){
            $apps = $this->sl->fn->server('conect/commit/'.$login.'/'.$pass,['POST'=>[
                'ver'=>$ver,
                'id'=>$id,
                'zip'=>'@'.SL_CACHE.DIR_SEP.'archive_bridge.zip'
            ]]);
            
            $str .= '<div class="win_h_size t_p_r">';
            
            if($apps['success']){
                $str .= '<div class="t_p_a t_left_50 t_top_50" style="margin: -32px 0 0 -94px"><h1 style="font-size: 38px;" class="smooth">Успешно</h1><p style="padding-left: 5px">Модуль был обновлен и отправлен<br /> на модерацию, в скором времени<br /> он будет опубликован в маркете</p></div>';
            }
            else{
                $str .= '<div class="t_p_a t_left_50 t_top_50" style="margin: -32px 0 0 -94px"><h1 style="font-size: 38px;" class="smooth">Неудача</h1><p style="padding-left: 5px">Возникли проблемы при обновлении<br /> модуля, попробуйте повторить процедуру<br /> публикации модуля</p></div>';
            }
            
            $str .= '</div>';
        }
        else $this->sl->fn->info('Ошибка данных');
        
        if($p < 4 && $p > 0){
        
        $stepArr = ['Мои модули','Выбор файлов','Версия модуля'];
        $stepOp = ['','margin-left: 290px'];
        $stepArrDe = ['Выбор модуля для обновления архива и отправки модуля на модерацию','Выберите файлы или папки для архивации и отправки на сайт','Укажите новою версию модуля'.$this->sl->scin->input('bridge_ver',$ver,['regex'=>"[^0-9.]",'attr'=>['style'=>'width: 132px','onclick'=>"$('#{$this->modInfo[0]}_next_btn').fadeIn()"]])];
        
        $str .= '<div class="step_content t_left" style="width: 250px;'.$stepOp[$p-1].'">
            <div class="t_left"><h1 style="font-size: 74px; margin: 0; margin-top: -8px" class="smooth">'.$p.'</h1></div>
            <div class="t_left" style="width: 200px">
                <h1 style="font-size: 38px; margin: 0; margin-bottom: 4px; text-transform: none" class="smooth">Шаг</h1>
                <h3 style="font-size: 20px; text-transform: none" class="smooth">'.$stepArr[$p-1].'</h3>
                <p>'.$stepArrDe[$p-1].'</p>
                <div id="'.$this->modInfo[0].'_next_btn" onclick="'.$this->modInfo[0].'_next('.($p+1).','.$id.',\''.$ver.'\')"></div>
                </div>
        </div>';
        }
        
        return $str;
    }
    function add_zip(){
        if($this->sl->fn->check_ac('root')) return;
        
        $path = $_POST['path'];
        
        require_once(SL_PLUGINS.DIR_SEP.'pclzip.lib.php');
        
        $archive = new PclZip(SL_CACHE.DIR_SEP.'archive_bridge.zip');
        
        if(!empty($path)) $archive->add(SL_DIR.$path,PCLZIP_OPT_REMOVE_PATH, SL_DIR);
        
        $zip_ext = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING);
        
        $ul = "<ul class=\"jqueryFileTree\">";
        
        foreach($zip_ext as $arr){
            if($arr['folder']) $ul .= "<li class=\"directory\"><a href=\"#\" onclick=\"return false\">" .$arr['stored_filename'] . "</a></li>";
            else $ul .= "<li class=\"file\"><a href=\"#\" onclick=\"return false\">" . $arr['stored_filename'] . "</a></li>";
        }
        
        return $ul.'</ul>';
    }
    function clear_zip(){
        if($this->sl->fn->check_ac('root')) return;
        
        @unlink(SL_CACHE.DIR_SEP.'archive_bridge.zip');
    }
    function dir_show(){
        if($this->sl->fn->check_ac('root')) return;
        
        $root = SL_DIR;
        
        $_POST['dir'] = urldecode($_POST['dir']);
        
        if(empty($_POST['dir'])) $_POST['dir'] = '/';

        if( file_exists($root . $_POST['dir']) ) {
        	$files = scandir($root . $_POST['dir']);
        	natcasesort($files);
        	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
        		$ul = "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        		// All dirs
        		foreach( $files as $file ) {
        			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
        				$ul .= "<li class=\"directory collapsed\"><a href=\"#\" onclick=\"return false\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
        			}
        		}
        		// All files
        		foreach( $files as $file ) {
        			if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) ) {
        				$ext = preg_replace('/^.*\./', '', $file);
        				$ul .= "<li class=\"file ext_$ext\"><a href=\"#\" onclick=\"return false\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
        			}
        		}
        		$ul .= "</ul>";
                
                return $ul;
        	}
        }
    }
}
?>