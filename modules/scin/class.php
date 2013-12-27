<?
// SCIN
//
// Version 1.2
//
// Author Korner
// Sl SYSTEM
// 06 June 2012
//
// Visit http://sl-cms.com for more information
//
// Usage: $.sl('method',options)
//
// TERMS OF USE
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2012 Sl SYSTEM, LLC.
 
class scin{
    var $print_style = false;
    var $theme_style = '';
    private $tb = ['string'=>'','op'=>[]];
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    
    function __call($method, $params = false) {
        
    }
    
    private function style($type,$exn,$string,$prf = false){
        $Coplite = '';
        
        if(is_numeric($prf)) $pr['pr'] = $prf;
        elseif(is_array($prf)) $pr = $prf;
        elseif($prf && is_string($prf)) $pr['theme'] = $prf;
        
        $src = isset($pr['theme']) ? $pr['theme'] : $this->theme_style;
        
        if(is_array($string)){
            foreach($string as $name) $Coplite .= str_replace('{src}',$src.'/'.$name.'.'.$exn,$type);
        }
        elseif(count(explode(',',$string)) > 0){
            
            foreach(explode(',',$string) as $name) $Coplite .= str_replace('{src}',$src.'/'.$name.'.'.$exn,$type);
        }
        elseif($string !== '') $Coplite = str_replace('{src}',$src.'/'.$string.'.'.$exn,$type);
        
        if($this->print_style or $pr['pr']) echo $Coplite;
        
        return $Coplite;
    }
    
    function js($string,$pr = false){
        return $this->style('<script type="text/javascript" src="{src}"></script>'."\n",'js',$string,$pr);
    }
    function css($string,$pr = false){
        return $this->style('<link rel="stylesheet" href="{src}" type="text/css" />'."\n",'css',$string,$pr);
    }
    private function cache_style($name,$ex,$action = 'show',$pr = false,$param = false){
        $name = str_replace(['\\','/'],DIRECTORY_SEPARATOR,$name);
        
        if(strstr($name,DIRECTORY_SEPARATOR)){
            $name = explode(DIRECTORY_SEPARATOR,$name);
            $name = end($name);
        }
        $name = $this->sl->fn->replase($name);
        
        if($param && is_array($param)){
            foreach($param as $key=>$strs) $end_param[] = $key.'='.urlencode($strs);
        }
        
        $end_param = $end_param ? '&'.implode('&',$end_param) : '';
        
        if($name !== ''){
            if(file_exists(SL_DIR.'/modules/'.$name.'/media.'.$ex.'.php')){
                if($ex == 'css') $string .= "<script>!$('#cache_media_".$ex."_".$name.'_'.$action."').length && $('body').append('<link rel=\"stylesheet\" href=\"'+\$.sl('options','theme')+'modules/".$name."/media.".$ex.".php?module={$name}&action={$action}$end_param\" type=\"text/css\" id=\"cache_media_".$ex."_".$name.'_'.$action."\" />')</script>";
                if($ex == 'js') $string .= "<script>!$('#cache_media_".$ex."_".$name.'_'.$action."').length && $('body').append('<script type=\"text/javascript\" src=\"'+\$.sl('options','theme')+'modules/".$name."/media.".$ex.".php?module={$name}&action={$action}$end_param\"></scr'+'ipt><js id=\"cache_media_".$ex."_".$name.'_'.$action."\" />')</script>";
            }
            else $string = 'Cache_'.$ex.' '.$this->sl->fn->lang('медиа файл не найден в').' ('.$name.')';
        }
        else $string = 'Cache_'.$ex.' '.$this->sl->fn->lang('пустое значение');
        
        if($this->print_style || $pr['pr']) echo $string;
        
        return $string;
    }
    function cache_css($name = false,$param = false,$action = 'show',$pr = false){
        return $this->cache_style($name,'css',$action,$pr,$param);
    }
    function cache_js($name = false,$param = false,$action = 'show',$pr = false){
        return $this->cache_style($name,'js',$action,$pr,$param);
    }
    function table_td_op($param = false){
        $this->tb['op'] = [];
        if(!is_array($param)) $param = func_get_args();
        foreach($param as $in => $v) $this->tb['op'][intval($in)] = is_array($v) ? $v : (intval($v) > 0 ? ['width'=>$v.'px'] : []);
    }
    function table_td_add_op($param = false){
        if(!is_array($param)) $param = func_get_args();
        foreach($param as $in => $v) $this->tb['op'][intval($in)] = is_array($v) && is_array($this->tb['op'][intval($in)]) ? array_merge($this->tb['op'][intval($in)],$v) : (is_array($v) ? $v : (intval($v) > 0 ? ['width'=>$v.'px'] : []));
    }
    function table_td($content = '',$op = [],$re = true){
        if(is_array($op)) foreach($op as $n=>$v) $at[] = $n.'="'.$v.'"';
        $rs = '<td'.($at ? ' '.implode(' ',$at) : '').'>'.$content.'</td>';
        if($re) $this->tb['string'] .= $rs;
        return $rs;
    }
    function table_th($content = '',$op = []){
        if(is_array($op)) foreach($op as $n=>$v) $at[] = $n.'="'.$v.'"';
        $this->tb['string'] .= $rs = '<th'.($at ? ' '.implode(' ',$at) : '').'>'.$content.'</th>';
        return $rs;
    }
    function table_tr($arr = [],$id = false,$n_s = false){
        $this->tb['row']++;
        $this->tb['string'] .= $rs = '<tr class="row_'.($this->tb['row']%2 == 0 ? 1 : 2).'"'.($id && !is_array($id) ? ' id="tr_'.$id.'"' : '').'>';
        
        if(is_array($arr)){
            foreach($arr as $k=>$v){
                $rs .= $this->table_td($v,$this->tb['op'][$k]);
            }
        }
        else $this->tb['string'] .= $rd = $arr;
        
        $this->tb['string'] .= $rd = '</tr>';
        return $rs.$rd;
    }
    function table_dynamic_row($fn = '',$url = false,$top = 0){
        return $this->tb['string'] .= '<tr class="sl_add_row"'.($fn ? ' callback="'.$fn.'"' : '').(isset($top) ? ' top="'.$top.'"' : '').($url ? ' url="'.$url.'"' : '').'><td colspan="100">'.$this->sl->fn->lang('Добавить поле').'</td></tr>';
    }
    function table($op = ''){
        if(is_array($op)){
            $op['class'] = $op['class'] ? $op['class'].' sl_table' : 'sl_table';
            foreach($op as $n=>$v) $se[] = $n.'="'.$v.'"';
        }
        else $se[] = 'class="sl_table" id="'.$op.'"';
        $this->tb['string'] = '<table '.implode(' ',$se).'>'.$this->tb['string'].'</table>';
        return $this->tb['string'];
    }
    function table_form($op = ''){
        if(is_array($op)){
            $op['class'] = $op['class'] ? $op['class'].' sl_form' : 'sl_form';
            foreach($op as $n=>$v) $se[] = $n.'="'.$v.'"';
            $se = array_merge($se,['action'=>'javascript:this.preventDefault()','method'=>'post']);
        }
        else $se[] = 'class="sl_form" id="'.$op.'" action="javascript:this.preventDefault()" method="post"';
        $this->tb['string'] = '<form '.implode(' ',$se).'>'.$this->tb['string'].'</form>';
        return $this->tb['string'];
    }
    function form($string = '',$op = ''){
        if(is_array($op)){
            $op['class'] = $op['class'] ? $op['class'].' sl_form' : 'sl_form';
            foreach($op as $n=>$v) $se[] = $n.'="'.$v.'"';
            $se = array_merge($se,['action'=>'javascript:this.preventDefault()','method'=>'post']);
        }
        else $se[] = 'class="sl_form" id="'.$op.'" action="javascript:this.preventDefault()" method="post"';
        return '<form '.implode(' ',$se).'>'.$string.'</form>';
    }
    function table_add_string($st = ''){
        $this->tb['string'] = $this->tb['string'].$st;
        return $st;
    }
    function table_head($head = []){
        if(!is_array($head)) $head = func_get_args();
        $this->tb['string'] .= $rs = '<tr class="header">';
        foreach($head as $k=>$v){
            $rs .= $this->table_th($v,$this->tb['op'][$k]);
        }
        $this->tb['string'] .= $rs = '</tr>';
        return $rs;
    }
    private function table_bid($row,$n,$btn,$rs,$key = false){
        $e = [];
        $bn = $rs = $n_s = '';
        
        if(!$n || is_string($n)){
            $str = is_string($n) ? true : false;
            
            foreach($row as $i=>$v){
                if($str){
                    $n_s = str_replace('{id}',$row['id'],$n);
                    $n_s = str_replace('{row}',$i,$n_s);
                    $e[] = '<span class="sl_edit" onclick="'.$n_s.'">'.$this->sl->fn->substr($v,0,100).'</span>';
                } 
                else $e[] = $this->sl->fn->substr($v,0,100);
            }
        }
        else{
            foreach((is_array($n) ? $n : [$n]) as $c=>$f){
                if(is_callable($f)){
                    $e[] = $f($row[$c],$key,$row);
                }
                elseif(is_array($f)) $e[] = $f[0].$row[$c].$f[1];
                else $e[] = $row[$c];
            }
        }
        if($btn){
            foreach((is_array($btn) ? $btn : []) as $m=>$l){
                $y = [];
                $y['attr']['onclick'] = is_array($l) ? "$(this).sl('load','$l[0]/".$row['id']."',{back:false".($l[1] ? ",mode:'$l[1]'" : '')."},function(data){ $(this).parents('tr').fadeOut(function(){ $(this).remove(); $.sl('update_scroll'); $.sl('sort_table'); $l[2]}) })" : $l.'.apply( this,[\''.$row['id'].'\'])';
                
                if(is_array($l) && is_array($l[3])) $y['attr'] = array_merge($y['attr'],$l[3]);
                $y['attr']['onclick'] = str_replace('{id}',$row['id'],$y['attr']['onclick']);
                
                $y['attr']['onclick'] = preg_replace("'\{row\-([a-z0-9_]+)\}'sie","\$row['\\1'];",$y['attr']['onclick']);
                $bn .= $this->btn($m,$l ? $y : false);
            }
        
            $e[] = $bn;
        }
        $rs .= $this->table_tr($e,$row['id']);
        return $rs;
    }
    function table_dynamic($n = [],$btn = [],$tbn = '',$lim = [0,25],$navtbl = false){
        if($tbn != '' || is_array($tbn)){
            $rs = $nav = '';
            if(@get_class($tbn) == 'mysqli_result' || !is_array($tbn)){
                if($this->sl->db()){
                    $this->sl->db->get_while(function($row) use($n,$btn,$rs){
                        $rs .= $this->table_bid($row,$n,$btn,$rs,$row['id']);
                    },@get_class($tbn) == 'mysqli_result' ? $tbn : $this->sl->db->select($tbn,['LIMIT'=>$lim]));
                    
                    if($lim[2]){
                        $nav = $this->sl->nav->show($this->sl->db->count(($navtbl ? $navtbl : $this->sl->db->last_tbl_name),$lim[3]),$lim[1],$lim[0],$lim[2],true);
                        if($nav) $rs .= $this->table_tr($this->table_td($nav,['colspan'=>100],false));
                    }
                }
            }
            elseif(is_array($tbn)){
                $total = count($tbn);
                
                if($lim) $tbn = array_slice($tbn,ceil(((intval($lim[0]) < 1 ? : intval($lim[0]))-1) * intval($lim[1])),$lim[1]);
                
                foreach($tbn as $key=>$row){
                    $rs .= $this->table_bid($row,$n,$btn,$rs,$key);
                }
                if($lim[2]){
                    $nav = $this->sl->nav->show($total,$lim[1],$lim[0],$lim[2],true);
                    if($nav) $rs .= $this->table_tr($this->table_td($nav,['colspan'=>100],false));
                }
            }
        }
        return $rs.$nav;
    }
    function table_display(){
        return $this->tb['string'];
    }
    function table_clear(){
        $this->tb = ['string'=>'','op'=>[]];
    }
    function checkbox($n = ''){
        if(!is_array($n)) $n = ['name'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['name'=>$n['name'],'value'=>$a[1]];
            if(is_array($a[2])) $n = array_merge($n,$a[2]);
            else $n['callback'] = $a[2];
        }
        
        $n = array_merge([
            'name'=> 'none',
            'value'=> '0',
            'attr'=> [],
            'callback'=>'',
            'text'=>''
        ],$n);
        
        $n['value'] = intval($n['value']);
        
        $n['attr']['class'] = $n['attr']['class'] ? $n['attr']['class'].' sl_checkbox' : 'sl_checkbox';
        $n['attr']['class'] .= $n['value'] > 0 ? ' active': '';
        $n['attr']['onclick'] = $n['callback'] ?  $n['callback']."('{$n['name']}',$(this).find('input').val());".$n['attr']['onclick'] : $n['attr']['onclick'];
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';  
        
        return '<div '.implode(' ',$n['attr']).'><input type="hidden" name="'.$n['name'].'" value="'.$n['value'].'" /><span style="display: inline-block">'.$n['text'].'</span></div>';
    }
    function input($n = ''){
        if(!is_array($n)) $n = ['name'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['name'=>$n['name'],'value'=>$a[1]];
            if(is_array($a[2])) $n = array_merge($n,$a[2]);
        }
        
        $n = array_merge([
            'name'=> 'none',
            'value'=> '',
            'type'=> 'input',
            'attr'=> [],
            'holder'=>'',
            'bigedit'=>false,
            'pattern'=>''
        ],$n);
        
        $ex_ar = ['url','text','password','date','email','number','tel'];
        
        $n['attr']['class'] = $n['attr']['class'] ? $n['attr']['class'].' sl_input'.($n['invisible'] ? ' invisible' : '') : 'sl_input'.($n['invisible'] ? ' invisible' : '');
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';    
        return '<div '.implode(' ',$n['attr']).'><div><input type="'.(in_array($n['type'],$ex_ar) ? $n['type'] : 'text').'" value="'.$n['value'].'" name="'.$n['name'].($n['pattern'] ? '" pattern="'.$n['pattern'].'"' : '').'" placeholder="'.$n['holder'].'" spellcheck="'.($n['check'] ? 'true' : 'false').'"'.($n['regex'] ? ' onkeyup="this.value = this.value.replace(/'.$n['regex'].'/gi,\'\');"' : '').' /></div>'.($n['bigedit'] ? '<div class="bigedit"></div>' : '').'</div>';
    }
    function input_live($where,$fn = false,$json = true){
        $id = rand(0,999);
        $onkeyup = "clearTimeout(time_id_$id); var _this_$id = $(this); time_id_$id = setTimeout(function(){ $(_this_$id).sl('load','$where/'+$(_this_$id).find('input').val(),{back:false".($json ? ",dataType:'json'" : '')."},function(data){ ".($fn ? $fn : "$(_this_$id).sl('menu',data,{zIndex:999})")." }) },3000);";
        return $this->input(['holder'=>'Поиск..','attr'=>['class'=>'live','onkeyup'=>$onkeyup]]).'<script>var time_id_'.$id.';</script>';
    }
    function btn($n = ''){
        if(!is_array($n)) $n = ['name'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            if(is_array($a[1])) $n = array_merge($n,$a[1]);
            else $n['callback'] = $a[1];
        }
        
        $n = array_merge([
            'name'=> 'none',
            'attr'=> [],
            'callback'=>''
        ],$n);
        
        $call = is_array($n['callback']) ? true : false;
        
        $n['attr']['class'] = $n['attr']['class'] ? $n['attr']['class'].' sl_btn' : 'sl_btn';
        $n['callback'] = is_array($n['callback']) ? "$(this).sl('load','{$n['callback'][0]}/',{back:false".($n['callback'][1] ? ",mode:'{$n['callback'][1]}'" : '')."},function(data){ {$n['callback'][2]} })" : $n['callback'];
        $n['attr']['onclick'] = $n['callback'] ?  $n['callback'].($call ? '' : ".apply(this,['{$n['name']}']);").$n['attr']['onclick'] : $n['attr']['onclick'];
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';
        return '<div '.implode(' ',$n['attr']).'>'.$n['name'].'</div>';
    }
    function btn_group($n = ''){
        if(!is_array($n)) $fn = $n ? $n.'.apply(this,null)' : '';
        else{
            if(is_numeric(implode('',array_keys($n)))){
                $fn = "$(this).sl('load','{$n[0]}/',{back:false".($n[1] ? ",mode:'{$n[1]}'" : '')."},function(data){ {$n[2]} })";
            }
            else{
                foreach($n as $r=>$f){
                    $b .= '<li onclick="'.(is_array($f) ? "$(this).sl('load','{$f[0]}/',{back:false".($f[1] ? ",mode:'{$f[1]}'" : '')."},function(data){ {$f[2]} })": $f.'.apply(this,null)').'">'.$r.'</li>';
                }
            }
        }
        return '<ul class="sl_user_btn'.($fn ? ' one" onclick="'.$fn : '').'">'.$b.'</ul>';
    }
    function floating($f = ''){
        return '<div class="sl_floating" onclick="'.$f.'"></div>';
    }
    function radio($n = ''){
        if(!is_array($n)) $n = ['name'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['name'=>$n['name'],'val'=>$a[1],'value'=>$a[2]];
            if(is_array($a[3])) $n = array_merge($n,$a[3]);
            else $n['callback'] = $a[3];
        }
        
        $n = array_merge([
            'name'=> 'none',
            'value'=> 'on',
            'val'=> ['on','off'],
            'type'=> 'line',
            'attr'=> [],
            'callback'=>'',
            'reverse'=>false
        ],$n);
        
        $r = rand(111,9999);
        
        if(!$n['val'] || count($n['val']) == 0) $n['val'] = ($n['reverse'] ? ['off','on'] : ['on','off']);
        
        foreach($n['val'] as $c=>$v){
            $s = $n['value'] == ($n['reverse'] ? $c : $v) ? [' checked','cb-enable selected'] : ['','cb-disable'];
            $i .= '<input type="radio" name="'.$n['name'].'" value="'.($n['reverse'] ? $c : $v).'" id="radio_'.$r.'_'.$c.'"'.$s[0].' /><label'.($n['callback'] ? ( is_array($n['callback']) ? " onclick=\"$.sl('load','{$n['callback'][0]}/$v/$c',{mode:'{$n['callback'][1]}'},function(){ {$n['callback'][2]} })\"": ' onclick="'.$n['callback'].'(\''.$v.'\','.$c.')"') : '').' for="radio_'.$r.'_'.$c.'" class="'.$s[1].'"><span>'.$v.'</span></label>';
        }
        
        $n['attr']['class']= $n['attr']['class'] ? $n['attr']['class'].' sl_radio '.$n['type'] : 'sl_radio '.$n['type'];
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';
        return '<div '.implode(' ',$n['attr']).'>'.$i.'</div>';
    }
    function select($n = ''){
        if(!is_array($n)) $n = ['name'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['name'=>$n['name'],'val'=>$a[1],'value'=>$a[2]];
            if(is_array($a[3])) $n = array_merge($n,$a[3]);
            else $n['callback'] = $a[3];
        }
        
        $n = array_merge([
            'name'=> 'none',
            'value'=> 'on',
            'val'=> ['on','off'],
            'attr'=> [],
            'callback'=>''
        ],$n);
        
        if(!is_array($n['val'])) $n['val'] = ['on','off'];
        if(empty($n['val'])) return;
        
        $jk = array_values($n['val']);
        $lk = array_keys($n['val']);
        
        if(is_array($n['callback'])){
            $callName = $n['callback'][0];
            array_shift($n['callback']);
        }
        else $callName = $n['callback'];
        
        foreach($n['val'] as $c=>$v){
            $i .= '<li'.($n['callback'] ? ' onclick="'.(is_array($n['callback']) ? $callName : $n['callback']).'(\''.$c.'\''.(is_array($n['callback']) ? ','.implode(',',$n['callback']) : '').')"' : '').' val="'.$c.'" name="'.$v.'"'.($c == $n['value'] ? ' class="selected"' : '').'><span>'.$v.'</span></li>';
        }
    
        $n['attr']['class']= $n['attr']['class'] ? $n['attr']['class'].' sl_select' : 'sl_select';
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';
        return '<div '.implode(' ',$n['attr']).'><input type="hidden" name="'.$n['name'].'" value="'.($n['value'] ? $n['value'] : $lk[0]).'" /><div class="_data"><ul>'.$i.'</ul></div><div class="_display">'.($n['val'][$n['value']] ? $n['val'][$n['value']] : $jk[0]).'</div></div>';
        
    }
    function textarea($n = ''){
        if(!is_array($n)) $n = ['name'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['name'=>$n['name'],'value'=>$a[1]];
            if(is_array($a[2])) $n = array_merge($n,$a[2]);
        }
        
        $n = array_merge([
            'name'=> 'none',
            'value'=> '',
            'attr'=> []
        ],$n);
        
        $n['attr']['class'] = $n['attr']['class'] ? $n['attr']['class'].' sl_textarea'.($n['invisible'] ? ' invisible' : '') : 'sl_textarea'.($n['invisible'] ? ' invisible' : '');
        //$n['attr']['style'] = $n['attr']['style'] ? 'width:100%; height:inherit;'.$n['attr']['style'] : 'width:100%; height:inherit;';
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';
            
        return '<div '.implode(' ',$n['attr']).'><div><textarea name="'.$n['name'].'" spellcheck="'.($n['check'] ? 'true' : 'false').'">'.$n['value'].'</textarea></div>'.($n['bigedit'] ? '<div class="bigedit"></div>' : '').'</div>';
            
    }
    function slide($n = '',$op = []){
        if(!is_array($n)) $n = [$n];
        
        $j = 0;
        
        $op = array_merge([
            'minus'=> 0,
        ],$op);
        
        foreach($n as $c=>$v){
            $i .= '<div class="page win_h_size scrollbarInit" '.($op['minus'] ? 'minus="'.$op['minus'].'"' : '').' style="left:'.($j*100).'%"><div class="s_data">'.$v.'</div></div>';
            $t .= '<li style="width:'.(100 / count($n)).'%" rel="'.$j.'"'.($j == 0 ? ' class="active"' : '').'>'.$c.'</li>';
            $j++;
        }
        
        return '<div class="sl_slide win_h_size" '.($op['minus'] ? 'minus="'.$op['minus'].'"' : '').'>'.$i.'<ul class="title">'.$t.'</ul></div>';
    }
    function hint($n = ''){
        if(!is_array($n)) $n = ['value'=>$n];
        
        if(func_num_args() > 1){
            $a = func_get_args();
            $n = ['value'=>$a[0]];
            if(is_array($a[1])) $n = array_merge($n,$a[1]);
        }
        
        $n = array_merge([
            'value'=> '',
            'attr'=> []
        ],$n);
        
        $n['attr']['class'] = $n['attr']['class'] ? $n['attr']['class'].' sl_hint' : 'sl_hint';
        
        foreach($n['attr'] as $j=>$v) $n['attr'][$j] = $j.'="'.$v.'"';
            
        return '<div '.implode(' ',$n['attr']).' tip="'.str_replace('"','',$n['value']).'">?<div>';
    }
}
?>