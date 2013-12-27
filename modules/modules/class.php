<?
/**
 * @modules
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class modules{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function __call($class, $params = false) {
        
    }
    function show(){
        if($this->sl->fn->check_ac('root')) return;
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group(['/ajax/'.$this->modInfo[0].'/save','quiet']));
        
        $lang = $this->sl->fn->lang([
            'Модуль',
            'Название',
            'Описание',
            'Стиль',
            'Тип',
            'Удалить',
            'Да',
            'Вы действительно хотите удалить модуль'
        ]);
        
        $this->sl->scin->table_td_op(23,100,130,280,100,160,100,0);
        $this->sl->scin->table_head('',$lang[0],$lang[1],$lang[2],$lang[3],$lang[4],'Shell Action','');
        
        $scan = $this->sl->fn->scan(SL_DIR.DIR_SEP.'modules'.DIR_SEP);
        
        foreach($scan['dir'] as $name){
            
            $m_d = SL_DIR.DIR_SEP.'modules'.DIR_SEP.$name;
            
            $conf = $this->sl->fn->conf('get',$m_d.DIR_SEP.'info');
            
            $nar[$name] = array_merge(['id'=>$name],$conf);
            
            $newVal = [];
            
            $allClass = get_class_methods($this->sl->$name);
            
            foreach($allClass as $n) $newVal[$n] = $n;
            
            $class_methods[$name] = $newVal;
            
            unset($class_methods[$name]['init']);
        }
        
        $this->sl->scin->table_dynamic([
            'status'=>function($d,$id){
                return  $this->sl->scin->radio('save['.$id.'][status]',[1=>'on',0=>'off'],$d);
            },
            'id'=>['<b class="color">','</b>'],
            'title'=>function($d,$id){
                return  $this->sl->scin->input('save['.$id.'][title]',$d,['invisible'=>1,'bigedit'=>1]);
            },
            'info'=>function($d,$id){
                return  $this->sl->scin->input('save['.$id.'][info]',$d,['invisible'=>1,'bigedit'=>1]);
            },
            'style'=>function($d,$id){
                return  $this->sl->scin->radio('save['.$id.'][style]',['default','aero'],$d);
            },
            'type'=>function($d,$id){
                return  $this->sl->scin->input('save['.$id.'][type]',$d,['invisible'=>1,'bigedit'=>1]);
            },
            'shell'=>function($d,$id) use($class_methods){
                return  $this->sl->scin->select('save['.$id.'][shell]',$class_methods[$id],$d);
            }
        ],[$lang[5]=>$this->modInfo[0].'_delete'],($nar ? $nar : []),[0,1000]);
        
        $this->sl->scin->table($this->modInfo[0]);
        $this->sl->scin->table_form();
        
        return $this->sl->scin->cache_js(__DIR__,['lang'=>json_encode($lang)]).$this->sl->scin->table_display();

    }
    function delete($name){
        if($this->sl->fn->check_ac('root')) return;
        
        if(!$this->sl->fn->del_dir(SL_DIR.DIR_SEP.'modules'.DIR_SEP.$this->sl->fn->replase($name).DIR_SEP)) $this->sl->fn->info($this->sl->fn->lang('Модуль не найден или удален!'));
    }
    function save(){
        if($this->sl->fn->check_ac('root')) return;
        
        if(isset($_POST)){
            $_POST['save'] = $this->sl->fn->strip($_POST['save']);
            
            foreach($_POST['save'] as $k=>$st){
                $this->sl->fn->conf('update',SL_DIR.DIR_SEP.'modules'.DIR_SEP.$k.DIR_SEP.'info',$st);
            }
        }
    }
}
?>