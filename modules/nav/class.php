<?
/**
 * @nav
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class nav{
    
    function init($sl){
        $this->sl = $sl;
        $this->smooth = false;
    }
    function __call($m,$p){
        
    }
    private function fn_btn($f,$n){
        $p = "'+$(this).parents('._nav').find('input').val()+'";
        return is_array($f) ? "$(this).sl('load','$f[0]/$p',{back:false,mode:'$f[1]'},function(){ $f[2] });" : ($n ? str_replace("{n}",$p,$f) : "window.location='".str_replace("{n}",$p,$f)."'");
    }
    private function user_fun($p,$f){
        return is_array($f) ? "$(this).sl('load','$f[0]/$p',{back:false,mode:'$f[1]'},function(){ $f[2] });": str_replace("{n}",$p,$f);
    }
    function show($t,$l,$p,$f = '',$not = false){
        $p = intval($p);
        $l = intval($l);
        $t = intval(($t - 1) / $l) + 1;

        $p = $p < 1 ? 1 : $p;
        $p = $p > $t ? $t : $p;
        
        
        if ($p != 1){
            $ar['1'] = $this->user_fun(1,$f); 
            $ar['Назад'] = $this->user_fun($p - 1,$f); 
        }
        
        $ar[$p] = $this->user_fun($p,$f);
        
        if ($p != $t){
            $ar['Дальше'] = $this->user_fun($p + 1,$f);
            $ar[$t] = $this->user_fun($t,$f); 
        }
        
        if($t > 1) {
            foreach($ar as $n=>$ah) $rst .= '<a href="'.(is_array($f) ? '" onclick="'.$ah.'; return false;' : ($not ? '" onclick="'.$ah.'; return false;' : $ah)).'" class="t_block'.($n == $p ? ' active' : '').($this->smooth ? ' smooth' : '').'">'.$n.'</a>';
            
            return '<div class="_nav t_center">'.$rst.$this->sl->scin->input(['attr'=>['class'=>'t_block']]).$this->sl->scin->btn('Перейти',['attr'=>['class'=>'t_block','onclick'=>$this->fn_btn($f,$not)]]).'</div>'.$this->sl->scin->cache_css(__DIR__);
            
        }
    }
    function show_easy($t,$l,$p,$f = '',$not = false){
        $p = intval($p);
        $l = intval($l);
        $t = intval(($t - 1) / $l) + 1;

        $p = $p < 1 ? 1 : $p;
        $p = $p > $t ? $t : $p;
        
        
        if ($p != 1){
            $ar['1'] = $this->user_fun(1,$f); 
            $ar['Назад'] = $this->user_fun($p - 1,$f); 
        }
        
        $ar[$p] = $this->user_fun($p,$f);
        
        if ($p != $t){
            $ar['Дальше'] = $this->user_fun($p + 1,$f);
            $ar[$t] = $this->user_fun($t,$f); 
        }
        
        if($t > 1) {
            foreach($ar as $n=>$ah) $rst .= '<a href="'.(is_array($f) ? '" onclick="'.$ah.'; return false;' : ($not ? '" onclick="'.$ah.'; return false;' : $ah)).'" class="t_block'.($n == $p ? ' active' : '').($this->smooth ? ' smooth' : '').'">'.$n.'</a>';
            
            return $rst;
        }
    }
}
?>