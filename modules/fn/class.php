<?
/**
 * @fn
 * @author korner
 * @copyright SL-SYSTEM 2012
 * 
 * if($this->modInfo[5]) return; Если именно этот модуль был загружен с адресной строки
 * if($this->ajaxLoad) return; Если модуль был загружен через AJAX метод
 */
class fn{
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false, $globalAjaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->globalAjaxLoad = $globalAjaxLoad;
    }
    
    function __call($class, $params = false) {
        
    }
    
    function check_ac($who = 'admin',$return_error = true){
        $this->sl->auth->check_member();
        
        if($who == 'root') $ac = $this->sl->auth->member_id['login'] !== 'root' ? true : false;
        elseif($who == 'admin') $ac = !$this->sl->auth->member_id['admin_ac'] ? true : false;
        elseif($who == 'user') $ac = !$this->sl->auth->member_id ? true : false;
                
        if($ac && $return_error){
            if($this->globalAjaxLoad) $this->sl->fn->info('('.$this->modInfo[0].') У вас нет прав доступа');
            else return true;
        }
        elseif($ac) return true;                
    }
    
    function ifUri($x = false){
        global $uri_r;
        
        if(is_array($x)) $n = $x;
        else $n[] = $x;
        
        foreach($n as $l){
            if(preg_match('#^'.str_replace('#','\#',trim($l)).'#si',$uri_r,$m)){
                return $m; break;
            }
        }
    }
    
    function replase($string,$arr = []){
        
        $arr['regx'] = $arr['regx'] ? $arr['regx'] : 'a-z0-9_';
        $arr['add'] = $arr['add'] ? 'a-z0-9_'.$arr['add'] : $arr['regx'];

        return preg_replace("'[".(isset($arr['ac']) ? '' : '^').$arr['add']."]'si",'',$string);
    }
    
    function scan($dir){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        if(is_dir($dir)){
            $handle = @opendir( $dir );
    
        	while ( false !== ($file = @readdir( $handle )) ) {
        
        		if( @is_dir( $dir.$file ) and ($file != "." and $file != "..") ) {
        			  
                    $c_files['dir'][$file] = $file;
        			
        		}elseif($file != "." and $file != ".."){
        		    $c_files['file'][$file] = $file;
        		}
        	}
           @closedir($handle);
       }
       if(count($c_files['dir']) == 0)  $c_files['dir']  = array();
       if(count($c_files['file']) == 0) $c_files['file'] = array();
       
       return $c_files;
    }
    function conf($type,$file,$array = false){
        if($this->modInfo[5]) return;
        
        $file_get = unserialize(trim(@file_get_contents($file.'.data')));
        $file_get = is_array($file_get) ? $file_get : [];
        
        switch ($type){ 
        	case 'get':
                if(is_array($file_get)){
                    if($array) return $file_get[$array];
                    else return $file_get;
                }
        	break;
        
        	case 'set':
                @file_put_contents($file.'.data',serialize($array));
        	break;
        
        	case 'update':
                if(is_array($file_get)){
                    $file_get = array_merge($file_get, $array);
                    @file_put_contents($file.'.data',serialize($file_get));
                    return $file_get;
                }
                else $this->conf('set',$file,$array);
        	break;
            
            case 'delete':
                if(is_array($file_get)){
                    unset($file_get[$array]);
                    @file_put_contents($file.'.data',serialize($file_get));
                    return $file_get;
                }
        	break;
            
            case 'clear':
                @file_put_contents($file.'.data',serialize([]));
        	break;
        
        	default : return array();
        }
    }
    
    function substr($string,$start,$lenght = false,$e = '...'){
        $string = strip_tags($string);
        if(function_exists('mb_substr')) return mb_substr($string,$start,$lenght,'UTF-8').(strlen($string) > intval($lenght) ? $e : '');
        else return iconv_substr($string,$start,$lenght,'UTF-8').(strlen($string) > intval($lenght) ? $e : '');
    }
    function modName($path){
        $xp = explode(DIRECTORY_SEPARATOR,$path);
        return end($xp);
    }
    function del_dir($path){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        if(@file_exists($path) && is_dir($path)){
    		$dirHandle = @opendir($path);
    		while (false !== ($file = @readdir($dirHandle))){
    			if ($file!='.' && $file!='..'){
    				$tmpPath = $path.'/'.$file;
    				
    				if (is_dir($tmpPath)) $this->del_dir($tmpPath);
    	  			else @unlink($tmpPath);
    			}
    		}
    		@closedir($dirHandle);
    		
            if(@rmdir($path)) return true;
            else return false;
    	}
    	else return false;
    }
    function infomod($mn){
        if($this->modInfo[5]) return;
        
        $mn = $this->replase($mn);
        
        $md = SL_DIR.'/modules/'.$mn;
        
        $info = $this->conf('get',$md.'/info');
        
        if(file_exists($md.'/ico.png')) $info['ico_img'] = 1;
        if(file_exists($md.'/menu.png')) $info['menu_img'] = 1;
        
        return $info;
    }
    function typemod($type = '',$check = ''){
        $a = explode('-',$type);
        
        if($check && $check !== ''){
            if(in_array($check,$a)) return $a;
            else return false;
        }
        
        return $a;
    }
    
    /**
     * Ручное включение модуля
     */
     
    function modul_on($mn = '',$pass = ''){
        $mn = $this->replase($mn);
        
        if($this->conf('get',SL_DATA.DIR_SEP.'root')['password'] !== md5(md5($pass))){
            if($this->ajaxLoad) $this->info('Пароль не подходит');
            else 'Пароль не подходит';
        }
        
        if($mn !== '') $this->conf('update',SL_DIR.'/modules/'.$mn.'/info',['status'=>'on']);
        
        $info = 'Модуль ('.$mn.') включен';
        if($this->ajaxLoad) $this->info($info);
        else return $info;
    }
    function strip($str,$quot = false){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        if(is_array($str)){
            foreach($str as $key=>$val){
                if(is_array($val)) $return[$key] = $val;
                else{
                    if($quot) $val = preg_replace("'[\'\"]'si",'',$val);
                    $return[$key] = trim(stripcslashes($val));
                } 
            }
        }
        else{
            if($quot) $str = preg_replace("'[\'\"]'si",'',$str);
            $return = trim(stripcslashes($str));
        } 
        return $return;
    }
    function info($string = false){
        if($string){
            echo json_encode(['error'=>$string])."\n"; die();
        }
    }
    function _format_bytes($a_bytes = 0){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        if ($a_bytes < 1024) {
            return $a_bytes .' B';
        } elseif ($a_bytes < 1048576) {
            return round($a_bytes / 1024, 2) .' KB';
        } elseif ($a_bytes < 1073741824) {
            return round($a_bytes / 1048576, 2) . ' MB';
        } elseif ($a_bytes < 1099511627776) {
            return round($a_bytes / 1073741824, 2) . ' GB';
        } elseif ($a_bytes < 1125899906842624) {
            return round($a_bytes / 1099511627776, 2) .' TB';
        } elseif ($a_bytes < 1152921504606846976) {
            return round($a_bytes / 1125899906842624, 2) .' PB';
        } elseif ($a_bytes < 1180591620717411303424) {
            return round($a_bytes / 1152921504606846976, 2) .' EB';
        } elseif ($a_bytes < 1208925819614629174706176) {
            return round($a_bytes / 1180591620717411303424, 2) .' ZB';
        } else {
            return round($a_bytes / 1208925819614629174706176, 2) .' YB';
        }
    }
    function filesize($filename = false,$for = false){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        if(@is_file($filename) && file_exists($filename)){
            if($for) return $this->_format_bytes(intval(@filesize($filename)));
            else intval(@filesize($filename));
        }
        
        return 0;
    }
    function showme($one = false,$two = false,$tbl = ''){
        if($this->modInfo[5]) return;
        
        $one = is_array($one) ? $one : [intval($one)];
        $two = is_array($two) ? $two : [$two];
        
        $id = intval($one[0]);
        
        $two[0] = $this->replase($two[0]);
        $tblname = empty($two[0]) ? $tbl : $tbl.'_'.$two[0];
        
        return [$one,$two,[$id,$tblname,(empty($two[0]) ? 0 : $two[0])]];
    }
    
    /**
     * Сооединение с сервером SL
     */
     
    function server($url,$mass = []){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $conect = $this->sl->curl->get('sl-cms.com/ajax/'.$url,$mass);
        
        if(!$conect['error'] && $json = json_decode($conect['content'],true)){
            if($json['error']) $this->sl->fn->info($json['error']);
            else return $json;
        }
        else $this->sl->fn->info('Сервер недоступен');
    }
    function rus_date($date = false,$time = false) {
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $translate = array(
            "Monday" => "Понедельник",
            "Mon" => "Пн",
            "Tuesday" => "Вторник",
            "Tue" => "Вт",
            "Wednesday" => "Среда",
            "Wed" => "Ср",
            "Thursday" => "Четверг",
            "Thu" => "Чт",
            "Friday" => "Пятница",
            "Fri" => "Пт",
            "Saturday" => "Суббота",
            "Sat" => "Сб",
            "Sunday" => "Воскресенье",
            "Sun" => "Вс",
            "January" => "Января",
            "Jan" => "Янв",
            "February" => "Февраля",
            "Feb" => "Фев",
            "March" => "Марта",
            "Mar" => "Мар",
            "April" => "Апреля",
            "Apr" => "Апр",
            "May" => "Мая",
            "May" => "Мая",
            "June" => "Июня",
            "Jun" => "Июн",
            "July" => "Июля",
            "Jul" => "Июл",
            "August" => "Августа",
            "Aug" => "Авг",
            "September" => "Сентября",
            "Sep" => "Сен",
            "October" => "Октября",
            "Oct" => "Окт",
            "November" => "Ноября",
            "Nov" => "Ноя",
            "December" => "Декабря",
            "Dec" => "Дек",
            "st" => "ое",
            "nd" => "ое",
            "rd" => "е",
            "th" => "ое"
        );
        
        if ($date) return strtr(date($date, $time), $translate);
        else return strtr(date(func_get_arg(0)), $translate);
    }
    function install_tpl($module = false,$rece = false){
        if($this->modInfo[5]) return;
        if(!$module) return;
        
        $module = $this->replase($module);
        
        if(empty($module)) return;
        
        $dir_scan = SL_MODULES.DIR_SEP.$module.DIR_SEP.'tpl'.DIR_SEP;
        
        if(is_dir($dir_scan)){
            $scan = $this->scan($dir_scan);
            
            foreach($scan['file'] as $name){
                if(!file_exists(TPL_DIR.DIR_SEP.$name) || $rece) @copy($dir_scan.$name,TPL_DIR.DIR_SEP.$name);
            } 
        }
    }
    
    function arrayID($arr){
        $nar = [];
        foreach($arr as $kva=>$nvar) $nar[$kva] = array_merge(['id'=>$kva],$nvar);
        return $nar;
    }
    
    function checkModule($mods = []){
        if($this->modInfo[5]) return;
        
        if(!is_array($mods)) $mods[] = $mods;
        
        foreach($mods as $name){
            if(!empty($name)){
                if(!$this->sl->$name()){
                    $noFind[] = $name;
                }
            }
        }
        
        if($noFind){
            if($this->globalAjaxLoad) $this->info('Модуль-и ('.implode(',',$noFind).') не установлены');
            else return 'Модуль-и ('.implode(',',$noFind).') не установлены';
        }
    }
    
    function lang($lang = false,$big = false){
        if($_POST['lang'] && $this->ajaxLoad) $lang = json_decode($_POST['lang'],true);
        
        if($this->sl->lang()) return $big ? $this->sl->lang->outBig($lang) : $this->sl->lang->out($lang);
        else return $lang;
    }
    
    function extension($ex = [],$url = ''){
        if(!is_array($ex)) $exs[] = $ex;
        else $exs = $ex;
        
        $url = preg_replace("#[?](.*?)$#",'',$url);
        $url = explode('/',$url);
        $url = end($url);
        $url = explode('.',$url);
        $url = trim(end($url));
        
        if(in_array($url,$exs)) return true;
    }
}
?>