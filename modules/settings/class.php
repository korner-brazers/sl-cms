<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class settings{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0];
        $this->conf_path = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0].'_';
    }
    private function selConf($name = ''){
        $name = $this->sl->fn->replase($name);
        
        if($name !== '' && $name !== '0') return $this->conf_path.$name;
        else return $this->conf;
    }
    function get($n = false,$name = ''){
        
        if($this->modInfo[4]){
            if(!$this->ajaxLoad) return;
            if($this->sl->fn->check_ac('admin')) return;
        }
        
        $arr = $this->sl->fn->conf('get',$this->selConf($name));
        $b = [];
        if(is_array($n)){
            foreach($n as $va){
                if($arr[$va]['status']) $b[$va] = $arr[$va]['value'];
            } 
        }
        elseif($n){
            if($arr[$n]['status']) $b = $arr[$n]['value'];
            else $b = false;
        }
        else $b = $arr;
        return $b;
    }
    function check($n = ''){
        if(!$this->ajaxLoad) return;
        
        if(is_array($n)) $c = $n;
        else $c = explode(',',$n);
        
        $get = $this->get();
        
        foreach($c as $v){
            if($get[trim($v)] == ''){
                $this->sl->fn->info($this->sl->fn->lang('Праметр').' ('.trim($v).') '.$this->sl->fn->lang('не указан в настройках или не заполнен')); break;
            }
        }
    }
    function clear(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->fn->conf('clear',$this->selConf($name));
    }
    function set($n = 'none'){
        if($this->modInfo[5]) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $arr  = $this->sl->fn->conf('get',$this->conf);
        $arr  = is_array($arr) ? $arr : [];
        $args = func_get_args();
        $arr  = array_merge($arr,[$this->sl->fn->replase($n)=>['value'=>str_replace('"',"'",$args[1]),'descr'=>str_replace('"',"'",$args[2]),'status'=>intval($args[3])]]);
        $this->sl->fn->conf('set',$this->conf,$arr);
    }
    function saveall($name = ''){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if(isset($_POST)){
            $_POST = $this->sl->fn->strip($_POST);
            foreach($_POST['status'] as $k=>$st){
                $alt = '';
                $alt = $_POST['alt_name'][$k];
                if($alt !== ''){
                    $b[$alt] = ['value'=>str_replace('"',"'",$_POST['value'][$k]),'descr'=>str_replace('"',"'",$_POST['descr'][$k]),'status'=>intval($st)];
                }
            }
            if($b) $this->sl->fn->conf('set',$this->selConf($name),$b);
        }
    }
    function set_default($n = false,$name = ''){
        if($this->modInfo[5]) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if(!file_exists($this->selConf($name).'.data')){
            
            $n = is_array($n) ? $n : [];
            $b = [];
            
            foreach($n as $i=>$m){
                $i = $this->sl->fn->replase($i);
                
                if($i) $b[$i] = ['value'=>str_replace('"',"'",$m['value']),'descr'=>str_replace('"',"'",$m['descr']),'status'=>intval($m['status'])];
            }
            
            $this->sl->fn->conf('set',$this->selConf($name),$b);
        }
    }
    function save_default($name = ''){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        @unlink($this->selConf($name).'_default.data');
        @copy($this->selConf($name).'.data',$this->selConf($name).'_default.data');
    }
    function recover($name = ''){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if(!file_exists($this->selConf($name).'_default.data')) $this->sl->fn->info($this->sl->fn->lang('Восстановление не может быть произведено поскольку не было сохранено по умолчанию'));
        
        @unlink($this->selConf($name).'.data');
        @copy($this->selConf($name).'_default.data',$this->selConf($name).'.data');
        
        return $this->show($name);
    }
    function other($rec = false,$name = ''){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $scan = $this->sl->fn->scan(SL_DATA.DIR_SEP);
        $on = [];
        
        foreach($scan['file'] as $name){
            if(strstr($name,'_'.$this->modInfo[0])){
                $on[] = str_replace('.data','',$name);
            }
        }
        
        if($rec){
            $i = intval($_POST[0]);

            if($on[$i] && file_exists(SL_DATA.DIR_SEP.$on[$i].'.data')){
                @copy(SL_DATA.DIR_SEP.$on[$i].'.data',$this->selConf($name).'.data');
                
                return $this->show($name);
            } 
            else $this->sl->fn->info($this->sl->fn->lang('Файл не найден для восстановления'));
            
        }
        
        return $on;
    }
    function show($name = ''){
        if($this->sl->fn->check_ac('admin')) return;
        
        $name = $this->sl->fn->replase($name);
        
        if(!$name){
            $this->set_default([
                'tpl_name'=>['value'=>'default','descr'=>'Название шаблона','status'=>1],
                'tpl_time'=>['value'=>'+ 1week','status'=>1],
                'tpl_title'=>['value'=>'Сайт на реконструкции','status'=>1]
            ]);
        }
        
        $lang = $this->sl->fn->lang([
            'Сохранить',
            'Очистить',
            'Сохранить по умолчанию',
            'Востановить',
            'Название',
            'Значение',
            'Описание',
            'Удалить',
            'Настройки восстановлены',
            'Использовать другие настройки'
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $lang[0]=>['/ajax/'.$this->modInfo[0].'/saveall/'.$name,'quiet'],
            $lang[1]=>['/ajax/'.$this->modInfo[0].'/clear/'.$name,'quiet'],
            $lang[2]=>['/ajax/'.$this->modInfo[0].'/save_default/'.$name,'quiet'],
            $lang[3]=>['/ajax/'.$this->modInfo[0].'/recover/'.$name,'quiet',"$('#co_{$this->modInfo[0]}_$name').html(data); $.sl('update_scroll'); $.sl('info','$lang[8]')"],
            $lang[9]=>"$(this).sl('scroll_menu',{load:'/ajax/{$this->modInfo[0]}/other',module: ['/ajax/{$this->modInfo[0]}/other/1/{$name}']},function(i,v,data){ $('#co_{$this->modInfo[0]}_$name').html(data); $.sl('update_scroll'); $.sl('info','$lang[8]') }); return false; l"
        ]));
        
        $this->sl->scin->table_td_op(20,160,200,0,80);
        $this->sl->scin->table_head('on-off',$lang[4],$lang[5],$lang[6],'');
        
        foreach($this->get(false,$name) as $kva=>$nvar){
            $nar[$kva] = array_merge(['id'=>$kva],$nvar);
        }
        
        $this->sl->scin->table_dynamic([
            'status'=>function($d){
                return  $this->sl->scin->checkbox('status[]',$d);
            },
            'id'=>function($d){
                return  $this->sl->scin->input('alt_name[]',$d,['regex'=>'[^a-z0-9_]']);
            },
            'value'=>function($d){
                return  $this->sl->scin->textarea('value[]',$d,['bigedit'=>true,'attr'=>['style'=>'height: 23px; line-height: 22px !important']]);
            },
            'descr'=>function($d){
                return  $this->sl->scin->input('descr[]',$d,['bigedit'=>true,'invisible'=>true]);
            }
        ],[$lang[7]=>[3=>['onclick'=>"$(this).sl('_tbl_del_tr')"]]],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/add_row',1,1);
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        return '<div id="co_'.$this->modInfo[0].'_'.$name.'">'.$this->sl->scin->table_display().'</div>';
    }
    function add_row(){
        if(!$this->ajaxLoad) return;
        
        return $this->sl->scin->table_tr([
            $this->sl->scin->checkbox('status[]',1),
            $this->sl->scin->input('alt_name[]','',['regex'=>'[^a-z0-9_]']),
            $this->sl->scin->textarea(['name'=>'value[]','bigedit'=>true,'attr'=>['style'=>'height: 23px; line-height: 22px !important']]),
            $this->sl->scin->input(['name'=>'descr[]','bigedit'=>true,'invisible'=>true]),
            $this->sl->scin->btn($this->sl->fn->lang('Удалить'),['attr'=>['onclick'=>"$(this).sl('_tbl_del_tr')"]])
        ]);
    }
}
?>