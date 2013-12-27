<?
/**
 * @cache
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class cache{
    var $time = 1440;
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->modInfo  = $moduleInfo;
    }
    
    function __call($name,$arr = []){
        
    }
    function clear($name){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        @unlink(SL_CACHE.DIR_SEP.preg_replace("'[^a-z0-9_]'si",'',$name).'.cache');
    }
    function __get($name){
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $f = SL_CACHE.DIR_SEP.$name.'.cache';
        if(file_exists($f)){
            $ctime = stat($f)[9];
            if(time() > $ctime + ($this->time * 60)){
                unlink($f);
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
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $f = SL_CACHE.DIR_SEP.$name.'.cache';
        if(is_array($value)) file_put_contents($f,serialize($value));
        else  file_put_contents($f,$value);
    }
}
?>