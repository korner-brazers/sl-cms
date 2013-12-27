<?
/**
 * @tpl
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class tpl{
    private $variables = [];
    private $result = '';
    
    function init($sl){
        $this->sl = $sl;
    }
    public function __set($name, $value){
        $this->variables[$name] = $value;
    }
    function display($name){
        extract($this->variables);
        global $moduleInfo;
        
        $template_file = TPL_DIR.DIR_SEP.$name.'.php';
        
        if(file_exists($template_file)){
            ob_start();
            include($template_file);
            $this->result .= $output = ob_get_contents();
            ob_end_clean();
        }
        else{
            $this->result .= $output = $sl->stpl->tpl_error("Шаблон (".$name.") не найден");
        }
        return !empty($output) ? $output : false;
    }
    function return_full($tpl = false){
        if($tpl) $this->display($tpl);
        $return = $this->result;
        $this->result = '';
        return $return;
    }
}
?>