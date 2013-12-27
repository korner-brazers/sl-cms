<?
/**
 * @desktop
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
class browser{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function __call($class, $params = false) {
        
    }
    function show(){
        $opera = stristr($_SERVER['HTTP_USER_AGENT'],'Opera') ? true : false;
        $ie = stristr($_SERVER['HTTP_USER_AGENT'],'MSIE') ? true : false;
        
        if($opera || $ie){
            include __DIR__.'show.php';
        }
    }
}
?>