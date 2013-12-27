<?
/**
 * @readme
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class readme{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    private function scanData($apps){
        $str = '';
        
        foreach($apps['file'] as $name){
            $ex = explode('.',$name);
            $ex = end($ex);
            
            if($ex == 'md'){
                
                $str .= '<li id="'.$name.'" style="height: 60px; display: block" class="data">
                    <div>
                        <div class="t_left con">
                            <span class="title">'.$this->sl->fn->substr($name,0,20).'</span>
                            <span class="descr">Размер: '.$this->sl->fn->filesize(SL_DATA.DIR_SEP.'readme'.DIR_SEP.$name,true).'</span>
                        </div>
                    </div>
                </li>';
            }
        }
        
        if(!empty($str)) return $str;
    }
    function show($where = 0){
        if($this->sl->fn->check_ac('admin')) return;
        
        $str   = '';
        $count = 0;
        $where = intval($where);
        $appsUrl = $where ? SL_DATA.DIR_SEP.'readme'.DIR_SEP : SL_MODULES.DIR_SEP;
        
        $apps = $this->sl->fn->scan($appsUrl);
        
        $str .= '<div class="'.$this->modInfo[0].'_bg win_h_size">';
        
        $str .= '<div class="win_h_size_shell" style="padding-left: 255px"><div class="win_h_size_shell scrollbarInit" id="'.$this->modInfo[0].'_fullReadme"></div></div>';
        
        $str .= '<div class="t_p_a t_left t_top win_h_size_shell" style="width: 255px"><div class="win_h_size_shell scrollbarInit '.$this->modInfo[0].'_apps" style="width: 255px"><ul class="'.$this->modInfo[0].'_apps_list t_ul">';
        
        if($where){
            if($sDas = $this->scanData($apps)){
                $str .= $sDas;
                $count = 1;
            }
        }
        else{
            foreach($apps['dir'] as $name){
                if(file_exists($appsUrl.$name.DIR_SEP.'readme.md')){
                    $info = $this->sl->fn->conf('get',$appsUrl.$name.DIR_SEP.'info');
                    
                    $ico = file_exists($appsUrl.$name.DIR_SEP.'ico.png') ? '/modules/'.$name.'/ico.png' : '/modules/'.$this->modInfo[0].'/defico.png';
                    
                    $str .= '<li id="'.$name.'" style="height: 60px; display: block">
                        <div>
                            <div class="ico t_left"><img src="'.$ico.'" /></div>
                            <div class="t_left con">
                                <span class="title">'.$this->sl->fn->substr($info['title'],0,20).'</span>
                                <span class="descr">'.$this->sl->fn->substr($info['info'],0,24).'</span>
                            </div>
                        </div>
                    </li>';
                    
                    $count++;
                }
            }
        }
        
        $str .= '</ul></div></div></div>';
        
        $lang = $this->sl->fn->lang([
            'Где искать',
            'Модули',
            'Искать документацию в модулях',
            'Другие',
            'Искать в папке'
        ]);
        
        $style = $this->sl->scin->cache_css(__DIR__).$this->sl->scin->cache_js(__DIR__,['lang'=>json_encode($lang)]).$this->sl->scin->floating($this->modInfo[0].'_menu()');
        
        if($count == 0) return '<div class="win_h_size t_p_r"><div class="t_p_a t_top_50 t_left t_width t_center">'.$this->sl->fn->lang('Не найдено не одного файла readme.md в модулях').' :(</div></div>'.$style;
        
        return $str.$style;
    }
    private function prcode($str){
        return highlight_string(htmlspecialchars_decode(stripcslashes(trim($str))),true);
    }
    private function code($str){
        return preg_replace("'pre>'si",'div>',preg_replace("'<code>(.*?)<\/code>'sie","\$this->prcode('\\1')",$str));
    }
    function loadReadme(){
        $id = $this->sl->fn->replase($_POST['id'],['add'=>'\_\-\.']);
        $md = $_POST['dt'] ? SL_DATA.DIR_SEP.'readme'.DIR_SEP.$id : SL_MODULES.DIR_SEP.$id.DIR_SEP.'readme.md';
        
        if(file_exists($md)){
            include SL_PLUGINS.DIR_SEP.'markdown.php';
            return '<div class="t_p_20 markdown">'.$this->code(Markdown(file_get_contents($md))).'</div>';
        }
    }
}
?>