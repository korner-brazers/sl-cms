<?
/**
 * @stpl
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class stpl{
    
    function init($sl){
        $this->sl = $sl;
    }
    
    function __call($name, $arr = []) {
        $stpl = SL_DIR.'/stpl/'.$this->sl->fn->replase($name).'.stpl';
        
        if(file_exists($stpl)){
            $loadstpl = file_get_contents($stpl);
            
            if(is_array($arr[0])){
                foreach($arr[0] as $n=>$v) $loadstpl = str_replace("{".$n."}",$v,$loadstpl);
            }
            elseif($arr[0] !== '')  $loadstpl = str_replace("{name}",$arr[0],$loadstpl);
            
            $loadstpl = preg_replace("'\{[a-z0-9_]+\}'",'',$loadstpl);
            
            return $loadstpl;
        }
        else return '<div class="noStpl">Не найден ('.$name.') stpl файл</div>';
    }
}
?>