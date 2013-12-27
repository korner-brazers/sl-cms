<?
/**
 * @category
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class category{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function get($str = '',$sub = true,$lim = false,$id = false){
        if($this->modInfo[5]) return;
        
        $where = $id ? (is_array($id) ? 'atc IN('.implode(',',$id).')' : 'atc='.$id) : false;
        $all   = $this->sl->db->select('category',['ORDER'=>'sort DESC','LIMIT'=>$lim,'WHERE'=>$where]);
        
        $all_arr = [];
        $sub_arr = [];
        $li      = '';
        
        while($m = $this->sl->db->get_row($all)){
            if($m['atc'] > 0 && !$id) $sub_arr[$m['atc']][$m['id']] = $m['name'];
            else $all_arr[$m['id']] = $m['name'];
        }
        
        foreach($all_arr as $id_main=>$name){
            $li .= str_replace('{sub}','',str_replace('{name}',$name,str_replace('{id}',$id_main,$str)));
            
            if($sub_arr[$id_main] && $sub){
                foreach($sub_arr[$id_main] as $id_sub=>$name_sub){
                    $li .= str_replace('{sub}',' sub',str_replace('{name}',$name_sub,str_replace('{id}',$id_sub,$str)));
                }
            }
        }
        
        return $li;
    }
    function addnew(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $max = $this->sl->db->count('category',['SELECT'=>'MAX(sort) as count']);
        
        $this->sl->db->insert('category',['name'=>strip_tags($_POST['name']),'visible'=>1,'sort'=>($max+1)]);

        $this->sl->scin->table_td_op([['class'=>'t_center t_bold'],3=>['class'=>'light t_center'],5=>['class'=>'light']]);
        
        $id = $this->sl->db->insert_id();    
        
        return $this->sl->scin->table_tr([
            $id,
            $this->sl->scin->radio('status',[1=>'on',0=>'off'],1,['reverse'=>true,'callback'=>['/ajax/'.$this->modInfo[0].'/chcat/'.$id]]),
            '<b style="cursor: pointer" onclick="'.$this->modInfo[0].'_change.apply(this,[\''.$id.'\'])">'.strip_tags($_POST['name']).'</b>',
            "<b style=\"cursor: pointer\" onclick=\"{$this->modInfo[0]}_menu.apply(this,['$id'])\">----</b>",
            '<input type="hidden" name="sort[]" value="'.$id.'" />',
            $this->sl->scin->btn('Удалить',['callback'=>['/ajax/'.$this->modInfo[0].'/delete/'.$id,'',"$(this).sl('_tbl_del_tr');"]])
        ]);
    }
    function podcat($id,$st){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $id = intval($id);
        
        if($st){
            $this->sl->db->update('category',['atc'=>intval($_POST[0])],$id);
            return $this->sl->db->select('category',intval($_POST[0]))['name'];
        }
        else{
            $this->sl->db->select('category',['ORDER'=>'sort DESC','WHERE'=>'atc=0 AND id != '.$id]);
            $arr[0] = '----';
            
            while($m = $this->sl->db->get_row()){
                $arr[$m['id']] = $m['name'];
            }
            
            return $arr;
        }
    }
    function chcat($id,$v,$c){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->update('category',['visible'=>intval($c)],intval($id));
    }
    function delete($id){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->delete('category',intval($id));
    }
    function change($id,$st){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $id = intval($id);

        if($st) $this->sl->db->update('category',['name'=>strip_tags($_POST[0])],$id);
        else{
            $sel = $this->sl->db->select('category',$id);
            return [['value'=>$sel['name']]];
        }
    }
    function savesort(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $sort = $_POST['sort'];
        
        if(!$sort || !is_array($sort)) $this->sl->fn->info('Ошибка данных');
        
        foreach($sort as $s=>$id){
            $this->sl->db->update('category',['sort'=>$s],$id);
        }
    }
    function show(){
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->alterTableAdd('category',[
            'name'=>['VARCHAR',100],
            'visible'=>['TINYINT',1,0],
            'sort'=>['SMALLINT',1,0],
            'atc'=>['SMALLINT',1,0]
        ]);
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            'Сохранить сортировку'=>['/ajax/'.$this->modInfo[0].'/savesort','quiet']
        ]));
        
        $this->sl->scin->table_td_op(20,20,200,200,0,80);
        $this->sl->scin->table_head('ID','','Название','Подкатегория','','');
        $this->sl->scin->table_td_op([['class'=>'t_center t_bold'],3=>['class'=>'light t_center'],5=>['class'=>'light']]);
        
        $this->sl->db->select('category',['ORDER'=>'sort DESC']);
        
        while($m = $this->sl->db->get_row()){
            $main[$m['id']] = $m;
        }
        
        foreach($main as $id=>$arr){
            if($arr['atc'] > 0){
                $arr['podcat'] = $main[$arr['atc']]['name'];
                $cat[$id] = $arr;
            }
            else $cat[$id] = $arr;
        }
        
        $this->sl->scin->table_dynamic([
            'id'=>'',
            'visible'=>function($d,$id,$row){
                return $this->sl->scin->radio('status',[1=>'on',0=>'off'],$d,['reverse'=>true,'callback'=>['/ajax/'.$this->modInfo[0].'/chcat/'.$row['id']]]);
            },
            'name'=>function($d,$id,$row){
                return '<b style="cursor: pointer" onclick="'.$this->modInfo[0].'_change.apply(this,[\''.$row['id'].'\'])">'.($d == '' ? '---' : $d).'</b>';
            },
            function($d,$id,$row){
                return  "<b style=\"cursor: pointer\" onclick=\"{$this->modInfo[0]}_menu.apply(this,['$row[id]'])\" onclick=\"$(this).sl('scroll_menu',{load:['/ajax/{$this->modInfo[0]}/podcat/$row[id]/get','quiet'],module:['/ajax/{$this->modInfo[0]}/podcat/$row[id]/save']},function(i,data){ $(this).text(data); })\">".($row['atc'] > 0 ? $row['podcat'] : '----').'</b>';
            },
            function($d,$id,$row){
                return '<input type="hidden" name="sort[]" value="'.$row['id'].'" />';
            }
        ],['Удалить'=>['/ajax/'.$this->modInfo[0].'/delete']],$cat,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'_add',0,1);
        $this->sl->scin->table(['id'=>$this->modInfo[0].'_table','class'=>'sortable']);
        $this->sl->scin->table_form();
        
        return '<div id="co_'.$this->modInfo[0].'">'.$this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__).'</div>';
    }
}
?>