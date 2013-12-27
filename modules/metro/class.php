<?
/**
 * @metro
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class metro{
    var $bl = [];
    var $row = [0];
    var $c = 1;
    var $bl_r = '';
    var $o = [
            'w'=>220,
            'h'=>125,
            'pad'=>20,
            'lim'=>4,
            'offset'=>5
    ];
    
    function init($sl){
        $this->sl = $sl;
    }
    
    function __call($name, $arr = []) {

    }
    
    function start($w = 0,$h = 0,$op = []){
        $this->bl = [];
        $this->row = [0];
        $this->c = 1;
        
        $oop = is_array($op) ? $op : false;
        $wop = is_array($w) ? $w : false;
        
        if(!is_array($w)) $this->o['w'] = intval($w) > 0 ? $w : $this->o['w'];
        if(!is_array($w)) $this->o['h'] = intval($h) > 0 ? $h : $this->o['h'];
        
        if($wop) $this->o = array_merge($this->o,$wop);
        if($oop) $this->o = array_merge($this->o,$oop);
    }
    function clear(){
        $this->bl_r = '';
    }
    function add($n = ''){
        
        if(!is_array($n)) $n = ['html'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['html'=>$n['html'],'fun'=>$a[1]];
            if(is_array($a[2])) $n = array_merge($n,$a[2]);
        }
        
        $n['w'] = intval($n['w']) == 0 ? 1 : $n['w'];
        $n['h'] = intval($n['h']) == 0 ? 1 : $n['h'];
        
        if(!$this->row[1]) $this->row[0] = $this->row[0] + $n['w'];
        
        $this->bl[] = $n;
        
        if($this->c >= $this->o['lim']) $this->sep();
        else $this->c = $this->c + $n['w'];
    }
    
    function block($bl = ''){
        if(!is_array($bl)) $bl = ['html'=>$bl];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $bl = ['html'=>$bl['html'],'fun'=>$a[1]];
            if(is_array($a[2])) $bl = array_merge($bl,$a[2]);
        }
        
        $bl['h'] = $bl['h'] ? intval($bl['h']) : $this->o['h'];
        
        $op = [
                $bl['h'] - $this->o['offset'],
                $bl['pad'] ? intval($bl['pad']) : $this->o['pad'],
            ];
            
            $op[] = $op[0] - ($op[1]*2);
        
        
        
        $bl['bg'] = $bl['bg'] ? ' background:'.$bl['bg'].';' : '';
        $bl['color'] = $bl['color'] ? ' color:'.$bl['color'].';' : '';
        $bl['fun'] = $bl['fun'] ? ' onclick="'.$bl['fun'].'"' : '';
        
        $bl['noresize'] = $bl['null'] ? true : $bl['noresize'];
        $r = !$bl['noresize'] ? '<div class="shine"></div>' : '';
        $tit = $bl['tit'] ? '<div class="tit">'.$bl['tit'].'</div>' : '';
        $null = $bl['null'] ? ' null' : '';
            
        $bl['attr']['class'] = $bl['attr']['class'] ? $bl['attr']['class'].' lay'.$null : 'lay'.$null;
            
        foreach($bl['attr'] as $j=>$v) $bl['attr'][$j] = $j.'="'.$v.'"'; 
            
        $data .= '<div class="t_p_r" style="height: '.$bl['h'].'px;">';
        $data .= '<div style="'.$bl['bg'].$bl['color'].'"'.$bl['fun'].' '.implode(' ',$bl['attr']).'>';
            
        $data .= '<div style="padding:'.$op[1].'px"><div class="hidebl" style="height:'.$op[2].'px;">'.$bl['html'].$tit.$r.'</div></div></div></div>';
        
        return $data;
    }
    
    function sep(){
        $this->bl[] = '</tr><tr>'; $this->c = 1; $this->row[1] = true;
    }
    
    function show($addHtml = '') {
        
        $data = '<table class="metro" style="width:'.($this->row[0] * $this->o['w']).'px;"><tr>';
        
        foreach($this->bl as $bl){
            
            if(!is_array($bl)){
                $data .= $bl; continue;
            }
            
            $op = [
                ($bl['h'] * $this->o['h']) + (($bl['h'] - 1) * $this->o['offset']),
                isset($bl['pad']) ? intval($bl['pad']) : $this->o['pad'],
            ];
            
            $op[] = $op[0] - ($op[1]*2);
            
            $bl['bg'] = $bl['bg'] ? ' background:'.$bl['bg'].';' : '';
            $bl['color'] = $bl['color'] ? ' color:'.$bl['color'].';' : '';
            $bl['fun'] = $bl['fun'] ? ' onclick="'.$bl['fun'].'"' : '';
            
            $bl['noresize'] = $bl['null'] ? true : $bl['noresize'];
            $r = !$bl['noresize'] ? '<div class="shine"></div>' : '';
            $tit = $bl['tit'] ? '<div class="tit">'.$bl['tit'].'</div>' : '';
            $null = $bl['null'] ? ' null' : '';
            $hide = isset($bl['hide']) ? '' : ' hide';
            
            $bl['attr']['class'] = $bl['attr']['class'] ? $bl['attr']['class'].' lay'.$null : 'lay'.$null;
            
            foreach($bl['attr'] as $j=>$v) $bl['attr'][$j] = $j.'="'.$v.'"';  
            
            $data .= '<td colspan="'.$bl['w'].'" rowspan="'.$bl['h'].'" style="width: '.($bl['w'] * $this->o['w']).'px; height: '.$op[0].'px; padding-bottom: '.$this->o['offset'].'px">';
            
            $data .= '<div class="pl t_p_r" style="margin-right: '.$this->o['offset'].'px">';
            $data .= '<div style="'.$bl['bg'].$bl['color'].'"'.$bl['fun'].' '.implode(' ',$bl['attr']).'>';
            
            $data .= '<div style="padding:'.$op[1].'px"><div class="hidebl'.$hide.'" style="height:'.$op[2].'px;">'.$bl['html'].$tit.$r.'</div></div></div></div>';
            
            $data .= '</td>';
        }
        $data .= '</tr></table>';
        
        $data = '<div class="metro_page">'.$data.$addHtml.'</div>';
        
        $this->bl_r .= $data;
        
        return $data;
    }
    
    function show_all($id){
        return '<div class="metro_conteiner" id="'.$id.'">'.$this->bl_r.'</div>'.$this->sl->scin->cache_css(__DIR__).$this->sl->scin->cache_js(__DIR__).$this->clear();
    }
}
?>