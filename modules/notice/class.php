<?
/**
 * @notice
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class notice{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf_prew = SL_CACHE.DIR_SEP.$this->modInfo[0].'_prew';
        $this->conf_list = SL_CACHE.DIR_SEP.$this->modInfo[0].'_list';
        $this->conf_password = SL_DATA.DIR_SEP.$this->modInfo[0].'_password';
    }
    function __call($method,$param){
        
    }
    function add_new($password,$massage = false,$module = false,$action = false,$params = false){
        $root_pass = $this->sl->fn->conf('get',$this->conf_password,'password');
        
        if($root_pass !== md5($password)){
            if($this->ajaxLoad) $this->sl->fn->info('Пароль не совпадает');
            else return 'Пароль не совпадает';
        }
        $params = urldecode($params);
        
        if(isset($_POST['list']) && is_array($_POST['list'])){
            $massage= $_POST['list']['massage'];
            $module = $_POST['list']['module'];
            $action = $_POST['list']['action'];
            $params = $_POST['list']['params'];
        }
        
        if(!$massage){
            if($this->ajaxLoad) $this->sl->fn->info('Сообщение не указано');
            else return 'Сообщение не указано';
        }
        
        $this->sl->fn->conf('update',$this->conf_prew,[['massage'=>strip_tags($massage),'module'=>$this->sl->fn->replase($module),'action'=>$this->sl->fn->replase($action)]]);
        $this->sl->fn->conf('update',$this->conf_list,[['time'=>time(),'massage'=>strip_tags($massage),'module'=>$this->sl->fn->replase($module),'action'=>$this->sl->fn->replase($action),'params'=>str_replace(['"',"'"],'',strip_tags($params))]]);
        
    }
    function widget(){
        if($this->modInfo[5]) return;
        
        $lang = $this->sl->fn->lang([
            'У вас',
            'Сообщений'
        ]);
        
        $string = "<div style=\"width: 140px; cursor: pointer\" onclick=\"$.sl('shell',{name:'{$this->modInfo[0]}'})\"><img src=\"/modules/{$this->modInfo[0]}/images/smile_widget.png\" class=\"t_left\" style=\"margin-right: 5px\" />";
        $string .= "<p>$lang[0]<br />".count($this->sl->fn->conf('get',$this->conf_list))." $lang[1]</p></div>";
        return $string.$this->sl->scin->cache_js(__DIR__);
    }
    function show_prew(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $list = $this->sl->fn->conf('get',$this->conf_prew);
        
        $this->sl->fn->conf('clear',$this->conf_prew);
        
        $lang = $this->sl->fn->lang([
            'У вас',
            'уведомлений',
            'Уведовление'
        ]);
        
        if(count($list) > 0){
            if(count($list) > 1) $mas = $lang[0].' ('.count($list).') '.$lang[1];
            else $mas = $lang[2].': '.$list[0]['massage'];
            
            $this->sl->fn->info("<div onclick=\"$.sl('shell',{name:'{$this->modInfo[0]}'})\"><img src=\"/modules/{$this->modInfo[0]}/images/smile_small.png\" style=\"vertical-align: middle; margin-right: 5px\" class=\"t_clear\" />$mas</div>");
        }
        return [];
    }
    function set_password(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->fn->conf('set',$this->conf_password,['password'=>md5(md5(trim($_POST['password'])))]);
    }
    function show(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $lang = $this->sl->fn->lang([
            'Установить пароль',
            'Пароль',
            'Установить',
            'Время',
            'Сообшение',
            'Выполнить'
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            $lang[0]=>"$.sl('_promt',{title:'{$lang[1]}',autoclose:false,module:['/ajax/{$this->modInfo[0]}/set_password/'],input:['password'],btn:{'{$lang[2]}':null}}); return; l",
        ]));
        
        $this->sl->scin->table_td_op(200,0,80);
        $this->sl->scin->table_td_add_op([1=>['class'=>'light']]);
        $this->sl->scin->table_head($lang[3],$lang[4],'');
        
        
        $nar = $this->sl->fn->conf('get',$this->conf_list);
        $nar = array_reverse($nar);
        
        $this->sl->scin->table_dynamic([
            'time'=>function($d){
                return  date('l, j F Y H:i',$d);
            },
            'massage'=>['<p style="padding: 0; margin: 3px 0">','</p>'],
            function($d,$id) use($nar,$lang){
                return ($nar[$id]['module'] ? $this->sl->scin->btn($lang[5],['attr'=>['onclick'=>"$.sl('load','/ajax/{$nar[$id][module]}/{$nar[$id][action]}/{$nar[$id][params]}',{win:{name:'{$nar[$id][module]}',w:550,h:300}})"]]) : '');
            }
        ],false,$nar,[0,1000]);
        
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        $this->sl->fn->conf('clear',$this->conf_list);
        
        return $this->sl->scin->table_display();
    }
}
?>