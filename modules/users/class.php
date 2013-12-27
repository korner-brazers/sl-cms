<?
/**
 * @users
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class users{
    function init($sl,$moduleInfo = [],$ajaxLoad = false,$glAjax = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->glAjax = $glAjax;
        $this->ho_gr_file = SL_DATA.DIR_SEP.MULTI_DN.'_'.$moduleInfo[0].'_group.data';
    }
    private function while_list($list = ''){
        global $uri_r;
        
        $excluded = explode("\n",trim($list));
        $excluded = array_merge($excluded,['fn','desktop','auth','tpl']);
        
        foreach($excluded as $goex){
            $goex = trim($goex);
            if($goex !== ''){
                if(@substr($uri_r,0,strlen($goex)) == $goex){
                    $findEx = true;
                    break;
                }
            }
        }
        
        if($findEx) return true;
        else return false;
    }
    function get_init(){
        global $moduleInfo;
        
        if(!$this->sl->fn->check_ac('user',false) && $moduleInfo[0]){
            
            if($this->sl->auth->member_id['login'] == 'root') return;
            
            $findEx = $this->while_list(@file_get_contents($this->ho_gr_file));
            
            if($this->sl->auth->member_id['gr'] > 0 && !$findEx){
                $sel_group = $this->sl->db->select('user_group',intval($this->sl->auth->member_id['gr']));
                
                if($sel_group) $findEx = $this->while_list($sel_group['list_module']);
            }
            
            if($findEx) return;
            else{
                if($this->glAjax) $this->sl->fn->info('У вас нет прав доступа к этому модулю!');
                else $this->sl->stopModule = true;
            }
            
        }
    }
    function edit_row($st,$id,$row){
        $id = intval($id);
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        if($st == 'set') $this->sl->db->update('users',[$row=>$_POST[0]],$id);
        return $this->sl->db->select('users',$id)[$row];
    }
    function edit_row_group($st,$id,$row){
        $id = intval($id);
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        if($st == 'set') $this->sl->db->update('user_group',[$row=>$_POST[0]],$id);
        return $this->sl->db->select('user_group',$id)[$row];
    }
    function group($st,$id){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        if($st == 'set') $this->sl->db->update('users',['gr'=>$_POST[0]],intval($id));
        else{
            $this->gro[0] = 'Без группы';
            
            $this->sl->db->get_while(function($row) use($gro){
                $this->gro[$row['id']] = $row['name'];
            },'user_group');
            
            return $this->gro;
        }
    }
    function delete($id){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        $this->sl->db->delete('users',intval($id));
    }
    function delete_group($id){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        $this->sl->db->delete('user_group',intval($id));
    }
    function no_group($st = false){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($st == 'set') @file_put_contents($this->ho_gr_file,$_POST['area']);
        else return @file_get_contents($this->ho_gr_file);
    }
    function addnewuser($st = false){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($st){
            $ar = $_POST['ar'];
            
            if(!$ar || !is_array($ar)) $this->sl->fn->info('Ошибка данных');
            
            $this->sl->db->insert('users',$ar);
            
            $id = $this->sl->db->insert_id();
        
            $this->sl->scin->table_td_op([['class'=>'dark t_center t_bold'],['class'=>'light t_center'],3=>['class'=>'light t_center']]);
            
            $users = $this->sl->db->select('users',$id);
            
            foreach($users as $row=>$v){
                $gr[] = '<span class="sl_edit" onclick="'.$this->modInfo[0].'_edit_row.apply(this,[\''.$id.'\',\''.$row.'\'])">'.$this->sl->fn->substr($v,0,100).'</span>';
            }
            
            $gr = array_merge($gr,[
                $this->sl->scin->btn('Удалить',['attr'=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delete/$id',function(){ $(this).sl('_tbl_del_tr') });"]]).
                $this->sl->scin->btn('&#8482;',['attr'=>['onclick'=>"$(this).sl('scroll_menu',{load:['/ajax/{$this->modInfo[0]}/group/get/$id'],module:['/ajax/{$this->modInfo[0]}/group/set/$id']},'0');"]])
            ]);
            
            return $this->sl->scin->table_tr($gr,$id);
        }
        else{
            $field = $this->sl->db->show_field('users');
            
            $this->sl->scin->table_td_op(20,0,220);
        
            $this->sl->scin->table_head('','Название столбца','Значение');
            
            $this->sl->scin->table_td_add_op([['class'=>'dark t_center'],['class'=>'light t_bold']]);
        
            foreach($field as $name){
                if($name == 'id') continue;
                
                $this->sl->scin->table_tr([
                    '<img src="/modules/'.$this->modInfo[0].'/di.png"',$name,$this->sl->scin->input('ar['.$name.']','',['bigedit'=>true])
                ]);
            }
            
            $this->sl->scin->table();
            $this->sl->scin->table_form($this->modInfo[0].'_addUser');
            
            return $this->sl->scin->table_display();
        }
    }
    function show($page = 0,$like = false){
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->alterTableAdd('users',[
            'gr'=>['SMALLINT',1,0],
        ]);
        
        $this->sl->db->alterTableAdd('user_group',[
            'name'=>['VARCHAR',300],
            'list_module'=>['TEXT NOT NULL',false]
        ]);
        
        $page = intval($page);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            'Поиск'=>"$.sl('_promt',{title:'Поиск',btn:{'Найти':function(p,v){
                $.sl('shell',{name:'{$this->modInfo[0]}',add_param:'$page/'+v[0].value},'update');
            }},input:['search']}); return; l",
        ]));
        
        $this->sl->scin->table_td_op(20,20);
        
        $this->sl->scin->table_head(array_merge($this->sl->db->show_field('users'),['']));
        
        $this->sl->scin->table_td_op([['class'=>'dark t_center t_bold'],['class'=>'light t_center'],3=>['class'=>'light t_center']]);
        
        
        $lim = [$page,25,"$.sl('shell',{name:'{$this->modInfo[0]}',add_param:'{n}/$like'},'update');"];
        $query = ['LIMIT'=>$lim,'ORDER'=>'id DESC'];
        
        if($like){
            $query['LIKE'] = [(intval($like) > 0 ? 'id' : 'login'),$like];
            $lim[] = ['LIKE'=>$query['LIKE']];
        } 
        
        $users = $this->sl->db->select('users',$query);
        
        $this->sl->scin->table_dynamic($this->modInfo[0].'_edit_row.apply(this,[\'{id}\',\'{row}\'])',[
            'Удалить'=>[3=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delete/{id}',function(){ $(this).sl('_tbl_del_tr') });"]],
            '&#8482;'=>[3=>['onclick'=>"$(this).sl('scroll_menu',{load:['/ajax/{$this->modInfo[0]}/group/get/{id}'],module:['/ajax/{$this->modInfo[0]}/group/set/{id}']},'{row-gr}')"]]
        ],$users,$lim,'users');
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'_addnewuser',0,1);
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        $us = $this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__);
        
        $this->sl->scin->table_clear();
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            'Исключить модули'=>['/ajax/'.$this->modInfo[0].'/no_group/get','',"$.sl('_area',{value:data,module:['/ajax/{$this->modInfo[0]}/no_group/set'],btn:{'Сохранить':null},autoclose:false})"],
        ]));
        
        $this->sl->scin->table_td_op(20,20,260);
        
        $this->sl->scin->table_head(array_merge($this->sl->db->show_field('user_group'),['']));
        
        $this->sl->scin->table_td_op([['class'=>'dark t_center t_bold'],['class'=>'light t_center'],3=>['class'=>'light t_center']]);
        
        $user_group = $this->sl->db->select('user_group',['ORDER'=>'id DESC']);
        
        $this->sl->scin->table_dynamic($this->modInfo[0].'_edit_row_group.apply(this,[\'{id}\',\'{row}\'])',[
            'Удалить'=>[3=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delete_group/{id}',function(){ $(this).sl('_tbl_del_tr') });"]]
        ],$user_group);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/add_group',1,1);
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        $gr = $this->sl->scin->table_display();
        
        return $this->sl->scin->slide(['Пользователи'=>$us,'Группы'=>$gr]);
    }
    function add_group(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->insert('user_group',[
            'name'=>'new group'
        ]);
        
        $id = $this->sl->db->insert_id();
        
        $this->sl->scin->table_td_op([['class'=>'dark t_center t_bold'],['class'=>'light t_center'],3=>['class'=>'light t_center']]);
        
        $group = $this->sl->db->select('user_group',$id);
        
        foreach($group as $row=>$v){
            $gr[] = '<span class="sl_edit" onclick="'.$this->modInfo[0].'_edit_row_group.apply(this,[\''.$id.'\',\''.$row.'\'])">'.$this->sl->fn->substr($v,0,100).'</span>';
        }
        
        $gr = array_merge($gr,[$this->sl->scin->btn('Удалить',['attr'=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delete_group/$id',function(){ $(this).sl('_tbl_del_tr') });"]])]);
        
        return $this->sl->scin->table_tr($gr,$id);
    }
}
?>