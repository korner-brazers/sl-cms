<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class plugins{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0];
        $this->addjs = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0].'_addjs.data';
    }
    function get(){
        if(defined('ADMINFILE')) $sel = 2;
        else $sel = 1;
        
        $conf = $this->sl->fn->conf('get',$this->conf);
        
        $head = '';
        $allw = ['js','css'];
        
        foreach($conf as $arr){
            if($arr['view'] > 0){
                if($arr['view'] !== $sel) continue;
            }
            
            if($arr['status']){
                
                if($arr['type'] == 1){
                    $dir = SL_MODULES.DIR_SEP.$arr['file'].DIR_SEP;
                    
                    if(file_exists($dir.'plugin.css.php')) $head .= '<link rel="stylesheet" href="/modules/'.$arr['file'].'/plugin.css.php?module='.$arr['file'].'&action=show" type="text/css" />'."\n";
                    if(file_exists($dir.'plugin.js.php')) $head .= '<script type="text/javascript" src="/modules/'.$arr['file'].'/plugin.js.php?module='.$arr['file'].'&action=show"></script>'."\n";
                }
                else{
                    
                    $ex = explode('.',$arr['file']);
                    
                    $fo = end($ex);
                    
                    if(in_array($fo,$allw)){
                        if(file_exists(SL_DIR.DIR_SEP.'plugins'.DIR_SEP.'js'.DIR_SEP.$arr['file'])) $head .= $this->sl->scin->$fo(str_replace('.'.$fo,'',$arr['file']),'/plugins/js')."\n";
                        if(file_exists(SL_DIR.DIR_SEP.'plugins'.DIR_SEP.'css'.DIR_SEP.$arr['file'])) $head .= $this->sl->scin->$fo(str_replace('.'.$fo,'',$arr['file']),'/plugins/css')."\n";
                    }
                }
            }
        }
        $addjs =  @file_get_contents($this->addjs);
        
        if($addjs) $head .= '<script>'.$addjs."</script>\n";
        
        if($head) $this->sl->header .= $head;
    }
    function clear(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->fn->conf('clear',$this->conf);
    }
    function saveall(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if(isset($_POST)){
            foreach($_POST['status'] as $k=>$st){
                $b[$k] = ['status'=>($st == 'on' ? 1 : 0),'view'=>intval($_POST['view'][$k]),'type'=>intval($_POST['type'][$k]),'file'=>strip_tags($_POST['file'][$k])];
            }
            if($b) $this->sl->fn->conf('set',$this->conf,$b);
        }
    }
    function save_default(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        @unlink($this->conf.'_default.data');
        @copy($this->conf.'.data',$this->conf.'_default.data');
    }
    function recover(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if(!file_exists($this->conf.'_default.data')) $this->sl->fn->info($this->sl->fn->lang('Восстановление не может быть произведено поскольку не было сохранено по умолчанию'));
        
        @unlink($this->conf.'.data');
        @copy($this->conf.'_default.data',$this->conf.'.data');
        
        return $this->show();
    }
    private function scan_files($m = 'js',$rep = false){
        $scan = $this->sl->fn->scan(SL_DIR.DIR_SEP.'plugins'.DIR_SEP.$m.DIR_SEP.$rep);
        
        $lang = $this->sl->fn->lang([
            'папка','выбрать'
        ]);
            
        if(!$rep){
            $this->sl->scin->table_clear();
            $this->sl->scin->table_td_op(20,30,0,80);
            $this->sl->scin->table_td_add_op([1=>['style'=>'text-align: center','class'=>'light']]);
            
            $this->sl->scin->table_head('','',$m.' '.$lang[0],'');
        }
        
        foreach($scan['file'] as $file){
            $ex = explode('.',$file);
            $ex_i = trim(end($ex));
            
            if($ex_i == 'css' || $ex_i == 'js'){
                $this->sl->scin->table_tr([
                    ($rep ? '<img src="/modules/'.$this->modInfo[0].'/di.png"/>' : ''),
                    '<b>'.$ex_i.'</b>',
                    $rep.$file,
                    $this->sl->scin->btn($lang[1],['callback'=>['/ajax/'.$this->modInfo[0].'/add_new/0/'.urlencode($rep.$file),'',$this->modInfo[0]."_add_new(data);"]])
                ]);
            }
        }
        
        foreach($scan['dir'] as $dir){
            $this->scan_files($m,$rep.$dir.'/');
        }
        
        if(!$rep){
            $this->sl->scin->table();
            
            return $this->sl->scin->table_display();
        }
    }
    function list_show($m = 0){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($m == 1){
            $scan = $this->sl->fn->scan(SL_MODULES.DIR_SEP);
            
            $this->sl->scin->table_td_op([0=>['style'=>'text-align: left']]);
            
            $lang = $this->sl->fn->lang([
                'Название модулей','выбрать'
            ]);
        
            $this->sl->scin->table_head($lang[0],'');
            
            foreach($scan['dir'] as $file){
                $this->sl->scin->table_tr([
                    $file,$this->sl->scin->btn($lang[1],['callback'=>['/ajax/'.$this->modInfo[0].'/add_new/1/'.urlencode($file),'',$this->modInfo[0]."_add_new(data);"]])
                ]);
            }
            
            $this->sl->scin->table();
            
            return $this->sl->scin->table_display();
        }
        else return $this->scan_files('js').$this->scan_files('css');
    }
    function add_new($ty = 0,$file = ''){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->scin->table_td_op([1=>['class'=>'t_center'],['class'=>'light t_center'],4=>['class'=>'light']]);
        
        $file = strip_tags(urldecode($file));
        
        $lang = $this->sl->fn->lang([
            'Везде','Главная','Админка','Файл','Модуль','Удалить'
        ]);
        
        return $this->sl->scin->table_tr([
            $this->sl->scin->radio('status['.md5($file).']'),
            $this->sl->scin->select('view['.md5($file).']',[$lang[0],$lang[1],$lang[2]],0),
            '<b>'.($ty == 0 ? $lang[3] : $lang[4]).'</b><input type="hidden" name="type['.md5($file).']" value="'.intval($ty).'" />',
            strip_tags(urldecode($file)).'<input type="hidden" name="file['.md5($file).']" value="'.$file.'" />',
            $this->sl->scin->btn($lang[5],['attr'=>['onclick'=>"$(this).sl('_tbl_del_tr')"]])
        ]);
    }
    function jscript($st = 'show'){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($st == 'save')@file_put_contents($this->addjs,$_POST['area']);
        else return @file_get_contents($this->addjs);
    }
    function show(){
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $lang = $this->sl->fn->lang([
            'Сохранить',
            'Очистить',
            'Сохранить по умолчанию',
            'Востановить',
            'Настройки восстановлены',
            'Добавить скрипт',
            'Видимость',
            'Тип',
            'Файл',
            'Везде',
            'Главная',
            'Админка',
            'Модуль',
            'Удалить',
            'Из плугинов',
            'Из модулей'
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $lang[0]=>['/ajax/'.$this->modInfo[0].'/saveall','quiet'],
            $lang[1]=>['/ajax/'.$this->modInfo[0].'/clear','quiet'],
            $lang[2]=>['/ajax/'.$this->modInfo[0].'/save_default','quiet'],
            $lang[3]=>['/ajax/'.$this->modInfo[0].'/recover','quiet',"$('#co_{$this->modInfo[0]}').html(data); $.sl('update_scroll'); $.sl('info','$lang[4]')"],
            $lang[5]=>['/ajax/'.$this->modInfo[0].'/jscript/show','',"$.sl('_area',{btn:{'Сохранить':null},value:data,autoclose:false,module:['/ajax/{$this->modInfo[0]}/jscript/save']})"]
        ]));
        
        $this->sl->scin->table_td_op(20,160,80,0,80);
        $this->sl->scin->table_head('',$lang[6],$lang[7],$lang[8],'');
        $this->sl->scin->table_td_op([1=>['class'=>'t_center'],['class'=>'light t_center'],4=>['class'=>'light']]);
        
        $nar = $this->sl->fn->conf('get',$this->conf);
        
        $this->sl->scin->table_dynamic([
            'status'=>function($d,$id,$row){
                return  $this->sl->scin->radio('status['.md5($row['file']).']',['on','off'],($d == 1 ? 'on' : 'off'));
            },
            'view'=>function($d,$id,$row) use($lang){
                return  $this->sl->scin->select('view['.md5($row['file']).']',[$lang[9],$lang[10],$lang[11]],$d);
            },
            'type'=>function($d,$id,$row) use($lang){
                return  '<b>'.($d == 0 ? $lang[8] : $lang[12]).'</b><input type="hidden" name="type['.md5($row['file']).']" value="'.$row['type'].'" />';
            },
            'file'=>function($d,$id,$row){
                return strip_tags($d).'<input type="hidden" name="file['.md5($row['file']).']" value="'.strip_tags($row['file']).'" />';
            }
        ],[$lang[13]=>[3=>['onclick'=>"$(this).sl('_tbl_del_tr')"]]],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'_add',0,1);
        $this->sl->scin->table(['id'=>$this->modInfo[0].'_table','class'=>'sortable']);
        $this->sl->scin->table_form();
        
        return '<div id="co_'.$this->modInfo[0].'">'.$this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__).'</div>';
    }
}
?>