<?
/**
 * @admin_menu
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class admin_menu{
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
    }
    function __call($name, $arr = []) {

    }
    private function bind($ty){
        $scan = $this->sl->fn->scan(SL_DIR.'/modules/');
        
        $this->sl->metro->clear();
        
        $this->sl->metro->start(225,130,['offset'=>5]);
        
        $i = 0;
        
        foreach($scan['dir'] as $name){
            $m_d = SL_DIR.'/modules/'.$name;
            
            $conf = $this->sl->fn->conf('get',$m_d.'/info');
            
            if($this->sl->fn->typemod($conf['type'],$ty == 'i' ? 'admin' : 'widget')){
                
                $hs = [
                    'w'=>1,
                    'pad'=>10,
                    'attr'=>['modname'=>$name,'type'=>$ty],
                ];
                
                if(file_exists($m_d.'/menu.png')) $hs['bg'] = '#1a1a1a url(/modules/'.$name.'/menu.png?'.rand(0,999).') no-repeat 50% 50%';
                else{
                    $hs['bg'] = '#1a1a1a';
                    $hs['html'] = '<h2 class="smooth color">'.$this->sl->fn->substr($conf['title'],0,10).'</h2><div style="height: 40px">'.$this->sl->fn->substr($conf['info'],0,92).'</div><div style="border-top: 1px solid #171717; border-bottom: 1px solid #242424; margin: 10px 0"></div><h3 class="smooth">'.$name.'</h3>';
                } 
                
                $this->sl->metro->add($hs);
                
                $i++;
                
                if($i >= 12){
                    $this->sl->metro->show();
                    $this->sl->metro->start(225,130,['offset'=>5]);
                    $i = 0;
                }
            }
        }
        
        $this->sl->metro->show();
        
        return $this->sl->metro->show_all('m_'.$ty);
    }
    function show() {
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $ico = $this->bind('i');
        $widget = $this->bind('w');
        $style = '
            <style>
                .a_m_bl{
                    -webkit-touch-callout: none;
                    -webkit-user-select: none;
                    -khtml-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                    user-select: none;
                }
            </style>
        ';
        return $this->sl->scin->slide(['Модули'=>'<div class="win_h_size a_m_bl" minus="25">'.$ico.'</div>','Виджеты'=>'<div class="win_h_size a_m_bl" minus="25">'.$widget.'</div>']).$this->sl->scin->cache_js(__DIR__)."<script>{$this->modInfo[0]}_start()</script>".$style;
        
    }
}
?>