<?
/**
 * @install
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class install{
    function init($sl,$moduleInfo = [],$ajaxLoad = false,$globalAjax = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->globalAjax = $globalAjax;
        $this->dir = SL_DATA.DIR_SEP.'install';
        $this->dirStage = SL_DATA.DIR_SEP.'install'.DIR_SEP.'stage';
        $this->dirProgress = SL_DATA.DIR_SEP.'install'.DIR_SEP.'progress';
        
        mkdir($this->dir);
        mkdir($this->dirStage);
        mkdir($this->dirProgress);
    }
    function __call($method,$param){
        
    }
    function check($name){
        if($this->modInfo[5]) return;
        if(empty($name) || is_array($name)) return;
        if(file_exists($this->dir.DIR_SEP.$name)) return true;
    }
    function next($name,$p = 0,$back){
        if($this->sl->fn->check_ac('admin')) return $this->sl->stpl->install_error();
        
        $p    = intval($p);
        $name = trim($name);
        $lang = $this->sl->fn->lang([
            'Ошибка данных при установки',
            'Модуль',
            'был успешно установлен, для отображения модуля попробуйте',
            'перезагрузить страницу',
            'Установка модуля',
            'Назад',
            'Дальше'
            
        ]);
        
        $ins = $this->sl->fn->conf('get',$this->dirStage.DIR_SEP.$name);
        
        if(!$ins) $this->sl->fn->info($lang[0]);
        
        $i = $ins['install'][$p];
        
        if(is_array($ins['op'])){
            $m = $ins['op'][0];
            $pr = array_shift($ins['op']);
        }
        else{
            $m = $ins['op'];
            $pr = [];
        }
        
        if($p > 0 && !$back){
            $get = $this->sl->fn->conf('get',$this->dirProgress.DIR_SEP.$name);
            $pm  = $p-1;
            
            $get[$pm] = [$_POST[$pm],$_POST];
            
            $this->sl->fn->conf('set',$this->dirProgress.DIR_SEP.$name,$get);
        }
        
        if(!$i){
            if(method_exists($this->sl->$name,'install')) $this->sl->$name->install($this->sl->fn->conf('get',$this->dirProgress.DIR_SEP.$name));
            
            file_put_contents($this->dir.DIR_SEP.$name,'');
            
            return '<div class="t_p_20">'.$this->sl->stpl->install($lang[1].' ('.$name.') '.$lang[2].' <a class="t_point" onclick="window.location = document.URL">'.$lang[3].'</a>').'</div>';
        }
        
        $html = '<div class="t_p_20"><h2>'.$lang[4].' ('.$name.')</h2>';
        
        if($i['title']) $html .= '<h3>'.strip_tags($i['title']).'</h3>';
        if($i['descr']) $html .= '<p>'.strip_tags($i['descr']).'</p>';
        
        $html .= '<form method="post" id="'.$name.'_install_post"><div style="width: 200px;">';
        
        if($i['html'])  $html .= $i['html'];
        
        $jn = ['radio','checkbox','select','input'];
        
        for($g = 0; $g < count($jn); $g++){
            $l = $jn[$g];
            
            if($i[$l]){
                
                if(is_array($i[$l])) $html .= $this->sl->scin->$l(array_merge($i[$l],['name'=>$p]));
                else $html .= $this->sl->scin->$l($p,$i[$l]);
                
                break;
            }
        }
        
        $html .= '</div><div class="t_sep t_clear" style="margin: 12px 0"></div>';
        
        if($p > 0) $html .= $this->sl->scin->btn($lang[5],['callback'=>['/ajax/'.$this->modInfo[0].'/next/'.$name.'/'.($p-1).'/1','',"$('#loadInstall_$name').html(data); $.sl('update_scroll')"]]);
        $html .= $this->sl->scin->btn($lang[6],['callback'=>['/ajax/'.$this->modInfo[0].'/next/'.$name.'/'.($p+1),'',"$('#loadInstall_$name').html(data); $.sl('update_scroll')"]]);
        
        $html .= '</form></div>';
        
        return $html;
    }
    function show($name,$install = [],$method = false){
        if($this->modInfo[5]) return;
        if($this->sl->fn->check_ac('admin')) return $this->sl->stpl->install_error();
        
        $name = $this->sl->fn->replase($name);
        
        if(empty($name)) return;
        
        if($this->check($name) || !is_array($install)) return;
        
        $op = [
            'op'=>$method,
            'ajax'=>$this->globalAjax ? 1 : 0,
            'install'=>$install
        ];
        
        $this->sl->fn->conf('set',$this->dirProgress.DIR_SEP.$name,[]);
        $this->sl->fn->conf('set',$this->dirStage.DIR_SEP.$name,$op);
        
        return $this->sl->scin->cache_css(__DIR__).'<div id="loadInstall_'.$name.'" class="installStyle">'.$this->next($name,0).'</div>';
    }
}
?>