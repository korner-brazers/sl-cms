<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class title{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0];
        $this->confDef = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0].'_def';
    }
    function get_init(){
        global $uri_r,$modul,$action,$params,$moduleInfo;
        
        $nar = $this->sl->fn->conf('get',$this->conf);
        
        foreach($nar as $title){
            if($title['status']){
                if($title['page'][0] == '/') $title['page'] = substr($title['page'],1,strlen($title['page']));
                
                if(stristr($uri_r,$title['page'])){
                    $this->titleAr = [
                        'title'=>$title['title'],
                        'desc'=>$title['desc'],
                        'keys'=>$title['keys'],
                    ];
                    
                    break;
                }
            }
        }
        
        if(!$this->titleAr){
            $this->titleAr = $this->sl->fn->conf('get',$this->confDef);
        }
    }
    function get($i = ''){
        return $this->titleAr[$i];
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
            foreach($_POST['status'] as $i=>$s){
                $b[] = [
                    'page'=>str_replace('"',"'",trim(strip_tags($_POST['page'][$i]))),
                    'title'=>str_replace('"',"'",trim(strip_tags($_POST['title'][$i]))),
                    'desc'=>str_replace('"',"'",trim(strip_tags($_POST['desc'][$i]))),
                    'keys'=>str_replace('"',"'",trim(strip_tags($_POST['keys'][$i]))),
                    'status'=>intval($s)
                ];
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
        
        if(!file_exists($this->conf.'_default.data')) $this->sl->fn->info('Восстановление не может быть произведено поскольку не было сохранено по умолчанию');
        
        @unlink($this->conf.'.data');
        @copy($this->conf.'_default.data',$this->conf.'.data');
        
        return $this->show();
    }
    function title_default($s = false){
        $conf = $this->sl->fn->conf('get',$this->confDef);
        
        if($s){
            $save = [
                'title'=>str_replace('"',"'",trim(strip_tags($_POST['title']))),
                'desc'=>str_replace('"',"'",trim(strip_tags($_POST['desc']))),
                'keys'=>str_replace('"',"'",trim(strip_tags($_POST['keys'])))
            ];
            
            $this->sl->fn->conf('set',$this->confDef,$save);
        }
        
        $this->sl->scin->table_td_op(0,100);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('title',$conf['title'],['bigedit'=>true]),
            'Титл сайта'
        ]);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('desc',$conf['desc'],['bigedit'=>true]),
            'Описание'
        ]);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('keys',$conf['keys'],['bigedit'=>true]),
            'Ключевые слова'
        ]);
        
        $this->sl->scin->table();
        
        return '<form method="post" id="'.$this->modInfo[0].'Default">'.$this->sl->scin->table_display().'</form>';
    }
    function show(){
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            'Сохранить'=>['/ajax/'.$this->modInfo[0].'/saveall/','quiet'],
            'Очистить'=>['/ajax/'.$this->modInfo[0].'/clear/','quiet'],
            'Сохранить по умолчанию'=>['/ajax/'.$this->modInfo[0].'/save_default/','quiet'],
            'Востановить'=>['/ajax/'.$this->modInfo[0].'/recover/','quiet',"$('#co_{$this->modInfo[0]}').html(data); $.sl('update_scroll'); $.sl('info','Настройки восстановлены')"],
            'Титл по умолчанию'=>['/ajax/'.$this->modInfo[0].'/title_default/','quiet',"$.sl('window',{data:data,bg:false,w: 300,h: 132,btn:{'Сохранить': function(){ $.sl('load','/ajax/{$this->modInfo[0]}/title_default/1',{data: $('form#{$this->modInfo[0]}Default').serializeArray()}) }}})"],
        ]));
        
        $this->sl->scin->table_td_op(20,160,200,200,0,80);
        $this->sl->scin->table_head('','Страница','Титл','Описание','Ключи','');
        
        $conf = $this->sl->fn->conf('get',$this->conf);
        
        $nar = $this->sl->fn->arrayID($conf);
        
        $this->sl->scin->table_dynamic([
            'status'=>function($d){
                return  $this->sl->scin->checkbox('status[]',$d);
            },
            'page'=>function($d){
                return  $this->sl->scin->input('page[]',$d,['bigedit'=>true]);
            },
            'title'=>function($d){
                return  $this->sl->scin->input('title[]',$d,['bigedit'=>true]);
            },
            'desc'=>function($d){
                return  $this->sl->scin->input('desc[]',$d,['bigedit'=>true]);
            },
            'keys'=>function($d){
                return  $this->sl->scin->input('keys[]',$d,['bigedit'=>true,'invisible'=>true]);
            }
        ],['Удалить'=>[3=>['onclick'=>"$(this).sl('_tbl_del_tr')"]]],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/add_row',1,false);
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        return '<div id="co_'.$this->modInfo[0].'_'.$name.'">'.$this->sl->scin->table_display().'</div>';
    }
    function add_row(){
        if(!$this->ajaxLoad) return;
        
        return $this->sl->scin->table_tr([
            $this->sl->scin->checkbox('status[]',1),
            $this->sl->scin->input('page[]','',['bigedit'=>true]),
            $this->sl->scin->input('title[]','',['bigedit'=>true]),
            $this->sl->scin->input('desc[]','',['bigedit'=>true]),
            $this->sl->scin->input(['name'=>'keys[]','bigedit'=>true,'invisible'=>true]),
            $this->sl->scin->btn('Удалить',['attr'=>['onclick'=>"$(this).sl('_tbl_del_tr')"]])
        ]);
    }
}
?>