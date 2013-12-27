<?
/**
 * @sl class
 * Глобальный класс SL
 * @author korner
 * @copyright SL-SYSTEM 2012
 */

if(!defined('SL_DIR')) die();
 
class sl{
    var $ajaxLoad = false;
    var $content = '';
    var $header  = '';
    var $stopModule = false;
    var $stopModuleThis = false;
    
    function load($class,$params = false){
        global $ajaxLoad,$moduleInfo;
        
        $classFile = SL_DIR.'/modules/' . $class . '/class.php';
        
        if (!class_exists($class)) {
            if (is_file ($classFile)){
                require_once ($classFile);
                
                $_class = new $class($params);
                
                $this->$class = $_class;
                
                /**
                 * Ишим название классов
                 */
                
                $arr = (array)$this;
                
                foreach($arr as $name=>$obj) $buldName[] = $name;
                
                $buldName = array_unique($buldName);
                
                /**
                 * Переписываем глобальный класс во всех классах
                 */
                
                foreach($buldName as $obj) if(method_exists($this->$obj, 'init')) $this->$obj->init($this,($moduleInfo && $moduleInfo[0] == $obj ? array_merge($moduleInfo,[0=>$obj]) : [$obj]),$obj == $moduleInfo[0] && !empty($moduleInfo[3]) ? 1 : 0,$ajaxLoad);
                
                /**
                 * Вызываем функцию типа __construct()
                 */
                 
                if(method_exists($_class, 'init_member')) $this->$class->init_member();
            }
            else{
                if($moduleInfo[0] == $class){
                    if($ajaxLoad) echo json_encode(['error'=>'Класс ('.$class.') не найден'])."\n";
                    else echo $this->stpl() ? $this->stpl->class_error($class) : '<div class="classError">Класс ('.$class.') не найден</div>';
                }
                return false;
            }
        }
        return true;
    }
    function __call($class, $params = false) {
        return $this->load($class,$params);
    }
    function __get($class){
        if($this->load($class)) return $this->$class;
        else return $this;
    }
    public function __toString(){
        return $this->content;
    }
}

$sl = new sl();
?>