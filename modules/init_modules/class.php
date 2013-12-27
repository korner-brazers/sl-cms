<?
/**
 * @init_modules
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class init_modules{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf = SL_DATA.DIR_SEP.'init_modules';
    }
    function __call($class, $params = false) {
        
    }
    private function scan_method($id = false){
        $scan = $this->sl->fn->scan(SL_DIR.'/modules/');
        
        foreach($scan['dir'] as $name){
            
            $m_d = SL_DIR.'/modules/'.$name;
            
            $conf = $this->sl->fn->conf('get',$m_d.'/info');
            
            $newVal = [];
            
            $allClass = get_class_methods($this->sl->$name);
            
            foreach($allClass as $n) $newVal[$n] = $n;
            
            $class_methods[$name] = $newVal;
            
            unset($class_methods[$name]['init'],$class_methods[$name]['__call']);
        }
        if($id) return $class_methods[$id];
        else return $class_methods;
    }
    function show(){
        if($this->sl->fn->check_ac('admin')) return;
        
        $lang = $this->sl->fn->lang([
            'Сохранить',
            'Сохранить по умолчанию',
            'Восстановить',
            'Настройки востановлены',
            'Модуль',
            'Где загружать',
            'Действие',
            'Если это модуль',
            'Исключить',
            'Название',
            'Описание',
            'В админ панели',
            'В индексе',
            'В индексе но без AJAX',
            'Везде',
            'Везде но без AJAX'
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $lang[0]=>['/ajax/'.$this->modInfo[0].'/save_all','quiet'],
            $lang[1]=>['/ajax/'.$this->modInfo[0].'/save_default','quiet'],
            $lang[2]=>['/ajax/'.$this->modInfo[0].'/recover','quiet',"$.sl('shell',{name:'{$this->modInfo[0]}'},'update'); $.sl('info','$lang[3]')"]
        ]));
        
        $this->sl->scin->table_td_op(20,180,180,150,150,150,150,0,0);
        $this->sl->scin->table_td_add_op([6=>['class'=>'t_bold t_center light'],1=>['class'=>'dark']]);
        $this->sl->scin->table_head('',$lang[4],$lang[5],$lang[6],$lang[7],$lang[8],$lang[9],$lang[10]);
        
        $conf = $this->sl->fn->conf('get',$this->conf);
        
        $scan = $this->sl->fn->scan(SL_DIR.'/modules/');
        
        foreach($conf as $name=>$arr){
            if(is_dir(SL_DIR.'/modules/'.$name)) $nar[$name] = array_merge(['id'=>$name],$arr);
        }
        
        foreach($scan['dir'] as $name){
            if(!$nar[$name]) $nar[$name] = array_merge(['id'=>$name],$this->sl->fn->conf('get',SL_DIR.'/modules/'.$name.'/info'));
        }
        
        $class_methods = $this->scan_method();
        
        $this->sl->scin->table_dynamic([
            'status'=>function($d,$id){
                return  $this->sl->scin->radio('status[]['.$id.']',[1=>'on',0=>'off'],($d == 1 ? 'on' : 'off'),['attr'=>['style'=>'display:block;float:left']]);
            },
            'id'=>function($d,$id){
                return '<input type="hidden" name="module[]" value="'.$id.'" /><b>'.$d.'</b>';
            },
            'type'=>function($v,$id) use($lang){
                return  $this->sl->scin->select('type[]',[1=>$lang[11],2=>$lang[12],3=>$lang[13],4=>$lang[14],5=>$lang[15]],$v);
            },
            'method'=>function($v,$id) use($class_methods){
                return  $this->sl->scin->select('method[]',$class_methods[$id],$v);
            },
            'is_module'=>function($v,$id){
                return  $this->sl->scin->textarea('is_module[]',$v,['bigedit'=>true,'attr'=>['style'=>'height: 23px; line-height: 22px !important']]);
            },
            'excluded'=>function($v,$id){
                return  $this->sl->scin->textarea('excluded[]',$v,['bigedit'=>true,'attr'=>['style'=>'height: 23px; line-height: 22px !important']]);
            },
            function($v,$id){
                return $this->sl->fn->conf('get',SL_DIR.DIR_SEP.'modules'.DIR_SEP.$id.DIR_SEP.'info')['title'];
            },
            function($v,$id){
                return $this->sl->fn->substr($this->sl->fn->conf('get',SL_DIR.DIR_SEP.'modules'.DIR_SEP.$id.DIR_SEP.'info')['info'],0,60);
            }
        ],false,($nar ? $nar : []),[0,100]);
        
        $this->sl->scin->table(['class'=>'sortable']);
        $this->sl->scin->table_form();
        
        return $this->sl->scin->table_display();
    }
    function save_all(){
        if(!$this->ajaxLoad) return;
        
        if($this->sl->fn->check_ac('admin')) return;
        
        if(isset($_POST)){
            $_POST = $this->sl->fn->strip($_POST);
            
            foreach($_POST['module'] as $k=>$mod){
                if($mod !== ''){
                    $b[$mod] = ['status'=>($_POST['status'][$k][$mod] == 'on' ? 1 : 0),'type'=>intval($_POST['type'][$k]),'method'=>$this->sl->fn->replase($_POST['method'][$k]),'is_module'=>$_POST['is_module'][$k],'excluded'=>$_POST['excluded'][$k]];
                }
            }
            if($b) $this->sl->fn->conf('set',$this->conf,$b);
        }
    }
    function save_default(){
        if(!$this->ajaxLoad) return;
        
        if($this->sl->fn->check_ac('admin')) return;
        
        @unlink(SL_DATA.DIR_SEP.'init_modules_default.data');
        @copy($this->conf.'.data',SL_DATA.DIR_SEP.'init_modules_default.data');
    }
    function recover(){
        if(!$this->ajaxLoad) return;
        
        if($this->sl->fn->check_ac('admin')) return;
        
         if(!file_exists(SL_DATA.DIR_SEP.'init_modules.data')) $this->sl->fn->info($this->sl->fn->lang('Восстановление не может быть произведено поскольку не было сохранено по умолчанию'));
         
        @unlink(SL_DATA.DIR_SEP.'init_modules.data');
        @copy(SL_DATA.DIR_SEP.'init_modules_default.data',$this->conf.'.data');
    }
    function save($name,$status = 0,$type = 3,$method = 'show',$is_module = '',$excluded = ''){
        if(!$this->ajaxLoad) return;
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $name = $this->sl->fn->replase($name);
        
        if($name == '') $this->sl->fn->info($this->sl->fn->lang('Параметр (name) не может быть пустым'));
        
        $status = $status == 1 ? 1 : 0;
        $type = intval($type);
        $method = $this->sl->fn->replase($method);
        
        $this->sl->fn->conf('update',$this->conf,[$name=>['status'=>$status,'type'=>$type,'method'=>$method,'is_module'=>strip_tags(urldecode($is_module)),'excluded'=>strip_tags(urldecode($excluded))]]);
    }
}
?>