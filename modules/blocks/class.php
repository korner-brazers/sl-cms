<?
/**
 * @blocks
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class blocks{
    var $bl = [];
    var $cout = 1;
    var $height = 125;
    
    function init($sl){
        $this->sl = $sl;
    }
    
    function __call($name, $arr = []) {

    }
    
    function add($arr){
        if(is_array($arr)) $this->bl[] = $arr;
    }
    function clear(){
        $this->bl = [];
        $this->cout = 1;
    }
    function count($limit = 4){
        if($this->cout >= intval($limit)){
            $this->sep();
            $this->cout = 1;
        } 
        else $this->cout++;
    }
    function sep(){
        $this->bl[] = '</tr><tr>';
    }
    function null($arr = []){
        $this->bl[] = '<td colspan="'.$this->w_h($arr)['w'].'" rowspan="'.$this->w_h($arr)['h'].'"></td>';
    }
    function title($title){
        $this->bl[] = '<tr><td class="title" colspan="4"><div>'.$title.'</div></td></tr>';
    }
    
    private function w_h($bl){
        $bl['w'] = intval($bl['w']) > 4 ? 4 : intval($bl['w']);
        $bl['w'] = $bl['w'] == 0 ? 1 : $bl['w'];
        
        $bl['h'] = intval($bl['h']) > 4 ? 4 : intval($bl['h']);
        $bl['h'] = $bl['h'] == 0 ? 1 : $bl['h'];
        
        return $bl;
    }
    
    function show() {
        $m = 0;
        
        if(count($this->bl) < 4){
            
            foreach($this->bl as $bw){
                $bw['w'] = intval($bw['w']);
                $bw['w'] = $bw['w'] == 0 ? 1 : $bw['w'];
                $m += $bw['w'];
            }
            $data .= '<table class="ulBlocks" style="width:'.(($m > 4 ? 4 : $m) * 25).'%"><tr>';
        }
        else $data .= '<table class="ulBlocks" style="width:100%"><tr>';
        
        foreach($this->bl as $bl){
            
            if(!is_array($bl)){
                $data .= $bl; continue;
            }
            
            $bl['w'] = $this->w_h($bl)['w'];
            $bl['h'] = $this->w_h($bl)['h'];
            
            $bl['bg'] = $bl['bg'] !== '' ? 'background:'.$bl['bg'].';' : '';
            $bl['color'] = $bl['color'] !== '' ? 'color:'.$bl['color'].';' : '';
            $bl['fun'] = $bl['fun'] !== '' ? 'onclick="'.$bl['fun'].'"' : '';
            
            $height = ($this->height * $bl['h']) + (10 * ($bl['h']-1)) - (intval($bl['pad']) * 2);
            
            $resize = !$bl['noresize'] ? '<div class="layer"></div>' : '';
            
            $rea = !$bl['noresize'] ? ' rea' : '';
            
            $class = $bl['class'] ? ' '.$bl['class'] : '';
            $outer_class = $bl['outer_class'] ? $bl['outer_class'].' ' : '';
            $attr = $bl['attr'] ? ' '.$bl['attr'] : '';
            $outer_attr = $bl['outer_attr'] ? ' '.$bl['outer_attr'] : '';
            $style = $bl['style'] ? ' '.$bl['style'] : '';
            
            $data .= '<td colspan="'.$bl['w'].'" rowspan="'.$bl['h'].'" class="'.$outer_class.'w_'.$bl['w'].' h_'.$bl['h'].'"'.$outer_attr.' '.$bl['fun'].'><div class="pl t_p_r"><div class="mar'.$rea.$class.' t_p_a t_top t_left t_width t_height"'.$attr.' style="'.$bl['bg'].$bl['color'].'"><div style="padding:'.intval($bl['pad']).'px"><div class="hidebl" style="height:'.$height.'px;'.$style.'">'.$bl['html'].$resize.'</div></div></div></div></td>';
        }
        $data .= '</tr></table>';
        
        return '<div id="blockConteiner">'.$data.'</div>'.$this->sl->scin->cache_css(__DIR__).$this->sl->scin->cache_js(__DIR__);
    }
}
?>