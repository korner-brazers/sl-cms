<?
/**
 * @field
 * @author korner
 * @copyright SL-SYSTEM 2012
 * Дополнительные поля
 */
 
class field{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function delete($id = 0,$tablename = ''){
        $tablename = empty($tablename) ? 'field' : 'field_'.$tablename;
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->delete($tablename,intval($id));
    }
    function delete_all($one = false,$two = false){
        $showme = $this->sl->fn->showme($one,$two,'field');
        
        $tbl   = $showme[2][1];
        $id    = $showme[2][0];
        
        if($this->sl->fn->check_ac('admin')) return;
        
        $this->sl->db->delete($tbl,'cid='.$id,false);
    }
    function show($one = false,$two = false){
        
        if(!$this->sl->fn->check_ac('admin')) $adm = true;
        
        $showme = $this->sl->fn->showme($one,$two,'field');
        
        $tbl   = $showme[2][1];
        $id    = $showme[2][0];
        
        $this->sl->db->alterTableAdd($tbl,[
            'name'=>['VARCHAR',300],
            'text'=>['TEXT NOT NULL',false]
        ]);
        
        $all = $this->sl->db->select($tbl,['ORDER'=>'id','WHERE'=>'cid='.$id]);
        
        $this->string = '<ul class="field">';
        
        if($this->sl->db->num_rows() > 0){
            $this->sl->db->get_while(function($row) use($showme){
                $this->string .= '<li'.($adm ? ' id="'.$row['id'].'" tbl="'.$showme[1][0].'"' : "").'>'.$row['name'].'<span>'.$row['text'].'</span></li>';
            });
        }
        
        if($adm) $this->string .= '<li class="addNew" tbl="'.$showme[1][0].'" cid="'.$showme[0][0].'">Добавить</li>';
        
        $this->string .= '</ul>';
        
        if($adm) $this->string .= $this->sl->scin->cache_js(__DIR__);
        
        return $this->string;
    }
    function edit($id,$s = false,$tblname = '',$cid = 0){
        if($this->sl->fn->check_ac('admin')) return;
        
        $tbl = empty($tblname) ? 'field' : 'field_'.$tblname;
        
        if($s){
            $arr = [
                'name'=>strip_tags($_POST['name']),
                'text'=>$_POST['text']
            ];
            
            if(!$id){
                $arr = array_merge($arr,['cid'=>$cid]);
                
                $this->sl->db->insert($tbl,$arr);
                $id = $this->sl->db->inser_id();
            } 
            else $this->sl->db->update($tbl,$arr,$id);
            
            return '<li id="'.$id.'" tbl="'.$tblname.'">'.strip_tags($_POST['name']).'<span>'.$_POST['text'].'</span></li>';
        }
        
        $row = $this->sl->db->select($tbl,intval($id));
        
        $this->sl->scin->table_tr([
            $this->sl->scin->input('name',$row['name']),
            'Название поля'
        ]);
        
        $this->sl->scin->table_tr([
            $this->sl->scin->textarea('text',$row['text'],['attr'=>['style'=>'height: 100px']]),
            'Значение поля'
        ]);
        
        $this->sl->scin->table();
        
        return $this->sl->scin->table_display();
    }
}
?>