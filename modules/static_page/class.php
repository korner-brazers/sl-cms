<?
/**
 * @static_page
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class static_page{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function init_member(){
        if(!$this->ajaxLoad) $this->stop = true;
        if($this->sl->fn->check_ac('admin')) $this->stop = true;
        if(!$this->sl->db->connect()) $this->stop = $this->sl->db->error;
    }
    function delete($id){
        if($this->stop) return $this->stop;
        
        $this->sl->db->delete('static',intval($id));
    }
    function delete_select(){
        if($this->stop) return $this->stop;
        
        $_POST['id'] = $_POST['id'] ? $_POST['id'] : [];
        foreach($_POST['id'] as $id=>$st) if($st == 1) $del[] = intval($id);
        if($del) $this->sl->db->delete('static',$del);
    }
    function onoff($id,$str,$int){
        if($this->stop) return $this->stop;
        
        $this->sl->db->update('static',['enabled'=>($str == 'on' ? 1 : 0)],intval($id));
    }
    function show_or_hide($id,$str,$int){
        if($this->stop) return $this->stop;
        
        $this->sl->db->update('static',['visible'=>($int == 0 ? 1 : 0)],intval($id));
    }
    function edit($id,$row,$json = false){
        if($this->stop) return $this->stop;
        
        $result = $this->sl->db->select('static',intval($id))[$this->sl->fn->replase($row)];
        if($json) return [['value'=>$result,'bigedit'=>true]];
        else return $result;
    }
    function save($id,$row,$key = false){
        if($this->stop) return $this->stop;
        
        if($row == 'temp' && $_POST[1] == 'default') $_POST[1] = '';
        
        $this->sl->db->update('static',[$this->sl->fn->replase($row)=>$_POST[($key ? intval($key) : 0)]],intval($id));
        if($row == 'full_news' || $row == 'short_news') $this->sl->fn->info('Страница была изменена');
    }
    function listcat(){
        if($this->stop) return $this->stop;
        
        $scan = $this->sl->fn->scan(TPL_DIR.DIR_SEP);
        
        foreach($scan['file'] as $file){
            $ex = explode('.',$file);
            
            if(end($ex) == 'php'){
                array_pop($ex);
                
                $name = implode('.',$ex);
                
                if($name == $this->modInfo[0]) $tpl[] = 'default';
                else $tpl[] = $name;
            }
        }
        return $tpl;
    }
    function cid(){
        if($this->stop) return $this->stop;
        
        $co = $this->sl->settings->get(['table','td'],$this->modInfo[0]);
        
        if($co['table'] && $co['td']){
            if($this->sl->db->connect(false)){
                
                $co['td'] = $this->sl->fn->replase($co['td']);
                
                $sel = $this->sl->db->select($co['table'],['SELECT'=>'id,'.$co['td'],'LIMIT'=>100],false);
                
                if($sel){
                    $r[0] = '---';
                    
                    while($row = $this->sl->db->get_row($sel)){
                        $r[$row['id']] = $this->sl->fn->substr($row[$co['td']],0,30);
                    }
                    
                    return $r;
                }
            }
        }
        
        return [];
    }
    function chCid($id = 0,$cid = 0){
        if($this->stop) return $this->stop;
        
        $this->sl->db->update('static',['cid'=>intval($cid)],intval($id));
    }
    private function createTable(){
        $this->sl->settings->set_default([
            'table'=>['value'=>'','descr'=>'Таблица выборки категорий','status'=>0],
            'td'=>['value'=>'','descr'=>'Ячейка таблицы с именем категории','status'=>0],
            'limit'=>['value'=>'15','descr'=>'Количество новостей на странице','status'=>0]
        ],$this->modInfo[0]);
        
        $this->sl->db->alterTableAdd('static',[
            'title'=>['VARCHAR',300],
            'enabled'=>['SMALLINT',1,0],
            'visible'=>['SMALLINT',1,0],
            'descr'=>['VARCHAR',300],
            'date'=>['DATETIME NOT NULL',false,'0000-00-00 00:00:00'],
            'temp'=>['VARCHAR',100],
            'full_news'=>['TEXT NOT NULL',false],
            'short_news'=>['TEXT NOT NULL',false],
        ]);
    }
    function show($page = 1,$like = false){
        $page = intval($page);
        
        if(!$this->sl->db->connect()) return $this->sl->db->error;
        
        $this->createTable();
        
        $this->sl->scin->table_add_string($this->sl->scin->btn_group([
            'Поиск'=>"$.sl('_promt',{title:'Поиск',btn:{'Найти':function(p,v){
                $.sl('shell',{name:'{$this->modInfo[0]}',add_param:'$page/'+v[0].value},'update');
            }},input:['test']}); return; l",
            //'Удалить выбранное'=>['/ajax/'.$this->modInfo[0].'/delete_select','',"$.sl('shell',{name:'{$this->modInfo[0]}',add_param:'$page/$like'},'update')"],
            'Настройки'=>['/ajax/settings/show/'.$this->modInfo[0],'',"$.sl('window',{name:'{$this->modInfo[0]}',data:data,w:700,h:350});"]
        ]));
        
        $this->sl->scin->table_td_op(20,60,40,20,0,300,130,160);
        
        $this->sl->scin->table_head('ID','CID','','','Название','Описание','Шаблон','');
        
        $this->sl->scin->table_td_op([0=>['class'=>'dark t_center'],['class'=>'light t_center'],5=>['class'=>'light'],['class'=>'t_center'],['class'=>'dark']]);
        
        $lim = [$page,25,"$.sl('shell',{name:'{$this->modInfo[0]}',add_param:'{n}/$like'},'update');"];
        $query = ['LIMIT'=>$lim,'ORDER'=>'id DESC'];
        
        if($like){
            $query['LIKE'] = [(intval($like) > 0 ? 'id' : 'title'),$like];
            $lim[] = ['LIKE'=>$query['LIKE']];
        } 
        
        $select = $this->sl->db->select('static',$query);
        
        $sh_list = $this->listcat();
        $cid = $this->cid();
        
        $this->sl->scin->table_dynamic([
            'id'=>['<b>','</b>'],
            'cid'=>function($v,$id) use($cid){
                if($cid) return $this->sl->scin->select('ca',$cid,$v,['callback'=>["{$this->modInfo[0]}_chCid",$id]]);
                else return "<b id=\"edit_cid\" cid=\"$id\" class=\"t_point\">".(empty($v) ? 0 : $this->sl->fn->substr($v,0,100)).'</b>'.' '.$this->sl->scin->hint('Определяет категория новости, редактируется вручную');
            },
            'enabled'=>function($v,$id){
                return $this->sl->scin->radio('onoff',['on','off'],($v == 1 ? 'on' : 'off'),['callback'=>['/ajax/'.$this->modInfo[0].'/onoff/'.$id]]);
            },
            'visible'=>function($v,$id){
                return $this->sl->scin->radio('onoff',['show','hide'],($v == 1 ? 'show' : 'hide'),['callback'=>['/ajax/'.$this->modInfo[0].'/show_or_hide/'.$id]]);
            },
            'title'=>function($v,$id){
                return "<b id=\"edit_page\" cid=\"$id\" class=\"t_point\">".(empty($v) ? '-- -- --' : $this->sl->fn->substr($v,0,100)).'</b>';
            },
            'descr'=>function($v,$id){
                return "<span id=\"edit_descr\" cid=\"$id\" class=\"t_point\">".(empty($v) ? '-- -- --' : $this->sl->fn->substr($v,0,100)).'</span>';
            },
            'temp'=>function($v,$id) use($sh_list){
                return $this->sl->scin->btn((empty($v) ? 'default' : $v),['attr'=>['style'=>'float: none; margin: 0','onclick'=>"$(this).sl('scroll_menu',{load:'/ajax/{$this->modInfo[0]}/listcat',module:['/ajax/{$this->modInfo[0]}/save/$id/temp/1']},'".intval(array_search((empty($v) ? 'default' : $v),$sh_list))."',function(nb,na){ $(this).text(na) });"]]);
            }
        ],[
            'Удалить'=>[3=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delete/{id}',function(){ $(this).sl('_tbl_del_tr') });"]],
            '&#8801;'=>[3=>['tip'=>'Полная','onclick'=>"$(this).sl('_area',{area_name:0,value:['/ajax/{$this->modInfo[0]}/edit/{id}/full_news'],bg:false,drag:true,size:true,resize:true,title:'Редактирование страницы',module:['/ajax/{$this->modInfo[0]}/save/{id}/full_news'],autoclose:false,btn:{'Сохранить':''}});"]],
            '&#926;'=>[3=>['tip'=>'Краткая','onclick'=>"$(this).sl('_area',{area_name:0,value:['/ajax/{$this->modInfo[0]}/edit/{id}/short_news'],bg:false,drag:true,size:true,resize:true,title:'Редактирование страницы',module:['/ajax/{$this->modInfo[0]}/save/{id}/short_news'],autoclose:false,btn:{'Сохранить':''}});"]],
        ],$select,$lim,'static');
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/add_row',1,1);
        $this->sl->scin->table($this->modInfo[0].'_table');
        $this->sl->scin->table_form();

        return $this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__);
    }
    function add_row(){
        if($this->stop) return $this->stop;
        
        $this->sl->db->insert('static',[
            'title'=>'static news',
            'news'=>'',
            'date'=>'',
            'visible'=>1,
            'enabled'=>1
        ]);
        
        $this->sl->scin->table_td_op([0=>['class'=>'dark t_center'],['class'=>'light t_center'],5=>['class'=>'light'],['class'=>'t_center'],['class'=>'dark']]);
        
        $id = $this->sl->db->insert_id();
        
        $sh_list = $this->listcat();
        $cid     = $this->cid();
        
        return $this->sl->scin->table_tr([
            '<b>'.$id.'</b>',
            ($cid ? $this->sl->scin->select('ca',$cid,0,['callback'=>["{$this->modInfo[0]}_chCid",$id]]) : "<b id=\"edit_cid\" cid=\"$id\" class=\"t_point\">0</b>".' '.$this->sl->scin->hint('Определяет категория новости, редактируется вручную')),
            $this->sl->scin->radio('onoff',['on','off'],'on',['callback'=>['/ajax/'.$this->modInfo[0].'/onoff/'.$id]]),
            $this->sl->scin->radio('onoff',['show','hide'],'show',['callback'=>['/ajax/'.$this->modInfo[0].'/show_or_hide/'.$id]]),
            "<b id=\"edit_page\" cid=\"$id\" class=\"t_point\">static news</b>",
            "<span id=\"edit_descr\" cid=\"$id\" class=\"t_point\">-- -- --</span>",
            $this->sl->scin->btn('default',['attr'=>['style'=>'float: none; margin: 0','onclick'=>"$(this).sl('scroll_menu',{load:'/ajax/{$this->modInfo[0]}/listcat',module:['/ajax/{$this->modInfo[0]}/save/$id/temp/1']},'".intval(array_search('default',$sh_list))."',function(nb,na){ $(this).text(na) });"]]),
            $this->sl->scin->btn('Удалить',['attr'=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delete/$id',function(){ $(this).sl('_tbl_del_tr') });"]]).$this->sl->scin->btn('&#8801;',['attr'=>['tip'=>'Полная','onclick'=>"$(this).sl('_area',{area_name:0,value:['/ajax/{$this->modInfo[0]}/edit/$id/full_news'],bg:false,drag:true,size:true,resize:true,title:'Редактирование страницы',module:['/ajax/{$this->modInfo[0]}/save/$id/full_news'],autoclose:false,btn:{'Сохранить':''}});"]]).$this->sl->scin->btn('&#926;',['attr'=>['tip'=>'Краткая','onclick'=>"$(this).sl('_area',{area_name:0,value:['/ajax/{$this->modInfo[0]}/edit/$id/short_news'],bg:false,drag:true,size:true,resize:true,title:'Редактирование страницы',module:['/ajax/{$this->modInfo[0]}/save/$id/short_news'],autoclose:false,btn:{'Сохранить':''}});"]])
        ],$id);
    }
    function all($page = 1,$cid = false, $tpl = false, $nav = true){
        if(!$this->sl->db->connect()) return $this->sl->db->error;
        
        if(!$this->sl->install->check($this->modInfo[0])){
            
            $lang = $this->sl->fn->lang([
                'Да - установить новые шаблоны если их нет',
                'Да - установить новые шаблоны даже если они установлены',
                'Нет - у меня уже установлены и настроены шаблоны новостей',
                'Установка шаблонов',
                'Для отображения новостей необходимы шаблоны '
            ]);
        
            return $this->sl->install->show($this->modInfo[0],[[
                    'title'=>$lang[3],
                    'descr'=>$lang[4],
                    'radio'=>['type'=>'list','val'=>[$lang[0],$lang[1],$lang[2]],'reverse'=>true],
                ]]);
        
        }
        
        $co = $this->sl->settings->get('limit',$this->modInfo[0]);
        
        $l = intval($co) > 0 ? $co : 15;
        
        $lim = is_array($page) ? $page : [$page,$l];
        
        $all = $this->sl->db->select('static',['LIMIT'=>$lim,'ORDER'=>'id DESC','WHERE'=>'enabled=1 AND visible=1'.($cid ? ' AND cid='.intval($cid) : '')]);
        
        if($this->sl->db->num_rows() > 0){
            $this->sl->db->get_while(function($row) use($tpl){
                $row['news'] = $row['short_news'] == '' ? $row['full_news'] : $row['short_news'];
                $this->sl->tpl->row = $row;
                $this->sl->tpl->display($tpl ? $tpl : (empty($row['temp']) ? $this->modInfo[0] : $row['temp']));
            },$all);
            
            if($nav) return $this->sl->tpl->return_full().$this->sl->nav->show($this->sl->db->count('static','enabled=1 AND visible=1'.($cid ? ' AND cid='.intval($cid) : '')),$l,$page,'/'.$this->modInfo[0].'/all/{n}/'.$cid);
            else return $this->sl->tpl->return_full();
        }
        else $this->sl->tpl->display($this->modInfo[0].'_error');
        return $this->sl->tpl->return_full();
    }
    function widget($cid = false,$lim = false){
        if($this->modInfo[5]) return;
        
        if(!$this->sl->db->connect()) return $this->sl->db->error;
        
        $lim = $lim && intval($lim) > 0 ? $lim : 5;
        
        $this->sl->db->get_while(function($row){
            $this->sl->tpl->row = $row;
            $this->sl->tpl->display($this->modInfo[0].'_widget');
        },$this->sl->db->select('static',['LIMIT'=>$lim,'WHERE'=>'enabled=1 AND visible=1'.($cid ? ' AND cid='.intval($cid) : ''),'ORDER'=>'id DESC']));
        
        return $this->sl->tpl->return_full();
    }
    function full($id = 0){
        if(!$this->sl->db->connect()) return $this->sl->db->error;
        
        $row = $this->sl->db->get_row($this->sl->db->select('static','enabled=1 AND id='.intval($id)));
        
        if($row){
            $row['news'] = $row['full_news'] == '' ? $row['short_news'] : $row['full_news'];
            $this->sl->tpl->row = $row;
            $this->sl->tpl->display(empty($row['temp']) ? $this->modInfo[0].'_section' : $row['temp']);
        }
        else $this->sl->tpl->display($this->modInfo[0].'_error');
        return $this->sl->tpl->return_full();
    }
    
    function live_search($string){
        if(!$this->ajaxLoad) return;
        
        if(!$this->sl->db->connect(false)) return [];
        
        $this->sl->db->select('static',['LIMIT'=>4,'LIKE'=>['title',$string]]);
        
        $this->sl->db->get_while(function($row){
            $this->search_arr[$this->sl->fn->substr($row['title'],0,20)] = "window.location = '/{$this->modInfo[0]}/full/{$row[id]}'";
        });
        
        return $this->search_arr;
    }
    function install($arr){
        if($this->modInfo[5]) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        if($arr[0][0] == 0) $this->sl->fn->install_tpl($this->modInfo[0]);
        elseif($arr[0][0] == 1) $this->sl->fn->install_tpl($this->modInfo[0],true);
        
        $this->createTable();
    }
}
?>