<?
/**
 * @logs
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class logs{
    var $time = 1440;
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->modInfo  = $moduleInfo;
    }
    function __call($name,$arr = []){
        
    }
    function clear($name){
        if($this->modInfo[4]) return;
        
        @unlink(SL_DIR.DIR_SEP.'logs'.DIR_SEP.preg_replace("'[^a-z0-9_]'si",'',$name).'.data');
    }
    function __get($name){
        if($this->modInfo[4]) return;
        
        $f = SL_DIR.DIR_SEP.'logs'.DIR_SEP.preg_replace("'[^a-z0-9_]'si",'',$name).'.data';
        
        if(file_exists($f) && is_file($f)){
            $ctime = stat($f)[9];
            if((time() > $ctime + ($this->time * 60)) || stat($f)[7] > (10000*1000)){
                @unlink($f);
                return false;
            }
            else{
                $data = file_get_contents($f);
                
                if(unserialize($data)) return unserialize($data);
                else return $data;
            }
        }
        else return false;
        
    }
    function __set($name,$value){
        if($this->modInfo[4]) return;
        
        $f = SL_DIR.DIR_SEP.'logs'.DIR_SEP.preg_replace("'[^a-z0-9_]'si",'',$name).'.data';
        
        if(is_array($value)) @file_put_contents($f,serialize($value));
        else  @file_put_contents($f,str_replace(SL_DIR.DIR_SEP,'',$value));
    }
}
?>