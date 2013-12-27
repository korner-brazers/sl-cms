<?
/**
 * @chpu
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class chpu{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0];
    }
    function get_init(){
        global $uri_r,$modul,$action,$params,$moduleInfo;
        
        $nar = $this->sl->fn->conf('get',$this->conf);
        
        foreach($nar as $chpu){
            if($chpu['status']){
                
                if(@preg_match('#^'.str_replace('#','\#',trim($chpu['get'])).'#si',$uri_r,$m)){
                    $moduleInfo[0] = $modul =  trim($chpu['mod']);
                    $moduleInfo[1] = $action = trim($chpu['action']);
                    
                    foreach($m as $i=>$v) $chpu['param'] = str_replace('{'.$i.'}',$v,$chpu['param']);
                    
                    $prs = explode('/',$chpu['param']);
                    
                    $moduleInfo[2] = $params = $prs;
                    
                    $uri_r = $modul.'/'.$action.'/'.$chpu['param'];
                    
                    break;
                }
            }
        }
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
            foreach($_POST['status'] as $id=>$st){
                if(trim($_POST['get'][$id]) !== ''){
                    $b[$id] = [
                        'get'=>str_replace('"',"'",$_POST['get'][$id]),
                        'mod'=>$this->sl->fn->replase($_POST['mod'][$id]),
                        'action'=>$this->sl->fn->replase($_POST['action'][$id]),
                        'param'=>str_replace('"',"'",$_POST['param'][$id]),
                        'status'=>intval($st)
                    ];
                }
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
    function show(){
        if($this->sl->fn->check_ac('admin')) return;
        
        $lang = $this->sl->fn->lang([
            'Сохранить',
            'Очистить',
            'Сохранить по умолчанию',
            'Востановить',
            'Определить URL',
            'Модуль',
            'Действие',
            'Параметры',
            'Удалить'
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $lang[0]=>['/ajax/'.$this->modInfo[0].'/saveall','quiet'],
            $lang[1]=>['/ajax/'.$this->modInfo[0].'/clear','quiet'],
            $lang[2]=>['/ajax/'.$this->modInfo[0].'/save_default','quiet'],
            $lang[3]=>['/ajax/'.$this->modInfo[0].'/recover','quiet',"$.sl('shell',{name:'{$this->modInfo[0]}'},'update')"]
        ]));
        
        $this->sl->scin->table_td_op(20,220,220,220,220,0);
        $this->sl->scin->table_head('',$lang[4],$lang[5],$lang[6],$lang[7],'');
        
        $this->sl->scin->table_td_add_op([['class'=>'dark'],2=>['class'=>'light'],4=>['class'=>'light']]);
        
        $nar = $this->sl->fn->conf('get',$this->conf);
        
        foreach($nar as $kva=>$nvar){
            $nar[$kva] = array_merge(['id'=>$kva],$nvar);
        }                
        
        $this->sl->scin->table_dynamic([
            'status'=>function($v,$id){
                return  $this->sl->scin->radio('status['.$id.']',false,$v,['reverse'=>true]);
            },
            'get'=>function($v,$id){
                return $this->sl->scin->input('get['.$id.']',$v,['bigedit'=>true]);
            },
            'mod'=>function($v,$id){
                return $this->sl->scin->input('mod['.$id.']',$v,['bigedit'=>true,'regex'=>'[^a-z0-9\_]']);
            },
            'action'=>function($v,$id){
                return $this->sl->scin->input('action['.$id.']',$v,['bigedit'=>true,'regex'=>'[^a-z0-9\_]']);
            },
            'param'=>function($v,$id){
                return $this->sl->scin->input('param['.$id.']',$v,['bigedit'=>true]);
            }
        ],[$lang[8]=>[3=>['onclick'=>"$(this).sl('_tbl_del_tr')"]]],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/add_row',1,1);
        $this->sl->scin->table(['class'=>'sortable']);
        $this->sl->scin->table_form();
        
        return $this->sl->scin->table_display();
    }
    function add_row(){
        if(!$this->ajaxLoad) return;
        
        $id = md5(time());
        
        $this->sl->scin->table_td_add_op([['class'=>'dark'],2=>['class'=>'light'],4=>['class'=>'light']]);
        
        return $this->sl->scin->table_tr([
            $this->sl->scin->radio('status['.$id.']',false,1,['reverse'=>true]),
            $this->sl->scin->input('get['.$id.']','',['bigedit'=>true]),
            $this->sl->scin->input('mod['.$id.']','',['bigedit'=>true,'regex'=>'[^a-z0-9\_]']),
            $this->sl->scin->input('action['.$id.']','',['bigedit'=>true,'regex'=>'[^a-z0-9\_]']),
            $this->sl->scin->input('param['.$id.']','',['bigedit'=>true]),
            $this->sl->scin->btn($this->sl->fn->lang('Удалить'),['attr'=>['onclick'=>"$(this).sl('_tbl_del_tr')"]])
        ],$id);
    }
}
?>