<?
/**
 * @comments
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class comments{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function delete($id = 0,$tablename = ''){
        $tablename = empty($tablename) ? 'comments' : 'comments_'.$tablename;
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->delete($tablename,intval($id));
    }
    function delete_all($one = false,$two = false){
        $showme = $this->sl->fn->showme($one,$two,'comments');
        
        $tbl   = $showme[2][1];
        $id    = $showme[2][0];
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->delete($tbl,'cid='.$id,false);
    }
    function show($one = false,$two = false,$page = 1){
        
        if(!$this->sl->install->check($this->modInfo[0])) return $this->sl->install->show($this->modInfo[0],$this->show_install());
        
        $this->sl->auth->check_member();
        
        $one = is_array($one) ? $one : [intval($one)];
        $two = is_array($two) ? $two : [$two];
        
        $id = intval($one[0]);
        
        $two[0] = $this->sl->fn->replase($two[0]);
        $tablename = empty($two[0]) ? 'comments' : 'comments_'.$two[0];
        
        $this->sl->db->alterTableAdd($tablename,[
            'user_name'=>['VARCHAR',100],
            'date'=>['DATETIME NOT NULL',false,'0000-00-00 00:00:00'],
            'text'=>['TEXT NOT NULL',false]
        ]);
        
        $conf = $this->sl->settings->get(['comments_lim','comments_any']);
        
        $l = intval($conf['comments_lim']) >= 1 ? $conf['comments_lim'] : 25;
        
        $all = $this->sl->db->select($tablename,['LIMIT'=>[$page,$l],'ORDER'=>'id','WHERE'=>'cid='.$id]);
        
        
        if($this->sl->db->num_rows() > 0){
            $this->sl->db->get_while(function($row) use($tablename,$two,$id){
                $row['text'] = strip_tags($row['text']);
                $this->sl->tpl->row = $row;
                if($this->sl->auth->member_id['admin_ac']) $this->sl->tpl->del_btn = "<div class=\"del_com\" onclick=\"$(this).sl('load','/ajax/{$this->modInfo[0]}/delete/$row[id]/{$two[0]}',{mode:'point'},function(){ $(this).parents('.com_block').slideUp(function(){ $(this).remove(); $.sl('update_scroll') }) })\">Удалить</div>";
                $this->sl->tpl->display($tablename);
            });
            
            $this->string_com = $this->sl->tpl->return_full();
        }
        
        
        $lang = $this->sl->fn->lang('Опубликовать');
        
        $this->sl->tpl->comments = '<div id="comments_block">'.$this->string_com.'</div>';
        $this->sl->tpl->textarea = $this->sl->scin->textarea('text');
        $this->sl->tpl->btn_add  = $this->sl->scin->btn($lang,['callback'=>["/ajax/{$this->modInfo[0]}/add/".$id."/{$two[0]}",'',"$(this).closest('.comments').find('#comments_block').append(data); $(this).closest('.comments').find('textarea').val(''); $.sl('update_scroll')"]]);
        $this->sl->tpl->nav      = $this->sl->nav->show($this->sl->db->count($tablename,'cid='.$id),$l,$page,'/'.$this->modInfo[0].'/show/'.$id.'/'.(empty($two[0]) ? 0 : $two[0]).'/{n}');
        
        if(!$this->sl->auth->member_id) $this->sl->tpl->name_user = $this->sl->scin->input(['name'=>'name','value'=>$_COOKIE['com_user_name'],'attr'=>['class'=>'t_block']]);
        
        if($conf['comments_any'] || $this->sl->auth->member_id) $this->sl->tpl->any = true;
        
        return $this->sl->scin->form('<div class="comments t_clearfix">'.$this->sl->tpl->return_full('form_comments').'</div>');

    }
    function widget($tablename,$lim = 7){
        if($this->modInfo[5]) return;
        
        $tablename = $this->sl->fn->replase($tablename);
        $tablename = empty($tablename) ? 'comments' : 'comments_'.$tablename;
        
        $this->sl->cache->time = 5;
        
        if($cache = $this->sl->cache->$tablename) return $cache;
        
        $all = $this->sl->db->select($tablename,['LIMIT'=>$lim,'ORDER'=>'id']);
        
        if($this->sl->db->num_rows() > 0){
            $this->sl->db->get_while(function($row) use($tablename){
                $row['text'] = strip_tags($row['text']);
                $this->sl->tpl->row = $row;
                $this->sl->tpl->display('widget_'.$tablename);
            });
            
            $cache = $this->sl->tpl->return_full();
            
            $this->sl->cache->$tablename = $cache;
            
            return $cache;
        }
    }
    function add($id,$tablename){
        
        $name = $_POST['name'];
        $text = $_POST['text'];
        
        $this->sl->auth->check_member();
        
        $lang = $this->sl->fn->lang([
            'Имя не указано',
            'Текст не заполнен'
        ]);
        
        if(!$this->sl->auth->member_id){
            if($name == '') $this->sl->fn->info($lang[0]);
        }
        else $name = $this->sl->auth->member_id['login'];
        
        if($text == '') $this->sl->fn->info($lang[1]);
        
        $di = $this->sl->fn->replase($tablename);
        
        $tablename = empty($di) ? 'comments' : 'comments_'.$di;
        
        $this->sl->db->insert($tablename,[
            'user_name'=>$name,
            'text'=>strip_tags($text),
            'date'=>'',
            'cid'=>intval($id)
        ]);
        
        $this->sl->auth->cookie("com_user_name", $name, 360);
        
        $id = $this->sl->db->insert_id();

        $row = $this->sl->db->select($tablename,intval($id));
        
        $row['text'] = str_replace("\n",'<br />',strip_tags($row['text']));
        
        $this->sl->tpl->row = $row;
        
        if($this->sl->auth->member_id['admin_ac']) $this->sl->tpl->del_btn = "<div class=\"del_com\" onclick=\"$(this).sl('load','/ajax/{$this->modInfo[0]}/delete/$id/$di',{mode:'point'},function(){ $(this).parents('.com_block').slideUp(function(){ $(this).remove() }) })\"></div>";
        
        $this->sl->tpl->display($tablename);
        
        return $this->sl->tpl->return_full();
    }
    function install($arr){
        if($this->modInfo[5]) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($arr[0][0] == 0 || $arr[0][0] == 1) $this->sl->fn->install_tpl($this->modInfo[0],$arr[0][0] == 1 ? true : false);
        
        $this->sl->settings->set('comments_lim',15,'Лимит комментариев на страницу',1);
        $this->sl->settings->set('comments_any',1,'(0-1) Позволяет добавлять комменты незарегистрированному пользователю',1);
    }
    function show_install(){
        if($this->modInfo[5]) return;
        
        $lang = $this->sl->fn->lang([
                'Да - установить новые шаблоны если их нет',
                'Да - установить новые шаблоны даже если они установлены',
                'Нет - у меня уже установлены и настроены шаблоны новостей',
                'Установка шаблонов',
                'Для отображения новостей необходимы шаблоны ',
                'Установка завершена',
                'Проверьте настройки, в них должны появится 2 параметра',
                'Опубликовать'
            ]);
        
        return [[
                'title'=>$lang[3],
                'descr'=>$lang[4],
                'radio'=>['type'=>'list','val'=>[$lang[0],$lang[1],$lang[2]],'reverse'=>true],
            ],[
                'title'=>$lang[5],
                'descr'=>$lang[6].' ( comments_lim, comments_any )',
            ]];
    }
}
?>