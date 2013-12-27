<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class buld{
    function __construct(){
        global $sl;
        
        if($sl->fn->check_ac('root')) $sl->stopModuleThis = $sl->fn->lang('Вы не являетесь супер админом ROOT');
    }
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->conf = SL_DATA.DIR_SEP.MULTI_DN.'_'.$this->modInfo[0];
        
        $this->BULD_DIR = SL_DATA.DIR_SEP.'buld';
        $this->BULD_LIB = $this->BULD_DIR.DIR_SEP.'lib';
        $this->BULD_PRJ = $this->BULD_DIR.DIR_SEP.'prj';
        $this->BULD_PRO = $this->BULD_DIR.DIR_SEP.'projected';
        
        @mkdir($this->BULD_DIR);
        @mkdir($this->BULD_LIB);
        @mkdir($this->BULD_LIB.DIR_SEP.'code');
        @mkdir($this->BULD_PRO);
        
        define('FILE_PUT',SL_CACHE.DIR_SEP.'buldPhpCode.php'); 
        
    }
    function delLib($id,$s = 0,$parent_id = 0,$lib_id = 0){
        $s = intval($s);
        
        if($s == 1){
            $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$parent_id);
            
            unset($lib['category'][$id]);
            
            $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$parent_id,$lib);
        }
        elseif($s == 2){
            $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id);
            
            unset($lib['category'][$parent_id]['obj'][$id]);
            
            $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$lib_id,$lib);
        }
        else @unlink($this->BULD_LIB.DIR_SEP.$id.'.data');
    }
    function addNewLib($s,$id = false,$lib_id = 0){
        if(!$this->ajaxLoad) return;
        
        $s      = intval($s);
        $new_id = md5(time());
        
        if($s == 1){
            $new_lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$id);
            
            if(!$new_lib) return;
            
            $name = 'Category name';
            $new_lib['category'][$new_id] = [
                'name'=>$name,
                'obj'=>[]
            ];
            
            $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$id,$new_lib);
        }
        elseif($s == 2){
            $new_lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id);
            
            if(!$new_lib) return;
            
            $name = 'Obj name';
            $new_lib['category'][$id]['obj'][$new_id] = [
                'name'=>$name,
                'op'=>[]
            ];
            
            $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$lib_id,$new_lib);
        }
        else{
            $name = 'libraly name';
            $new_lib = [
                'name'=>'libraly name',
                'category'=>[]
            ];
             
            $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$new_id,$new_lib);
        }
        
        $this->sl->scin->table_td_op([['class'=>'t_center t_bold dark'],['class'=>'t_bold']]);
        

        $lang = $this->sl->fn->lang([
            'Удалить','Сохранить','Редактировать'
        ]);
        
        return $this->sl->scin->table_tr([
            '<img src="/modules/'.$this->modInfo[0].'/media/img/'.($s == 1 ? 'objects_ico' : ($s == 2 ? 'plus' : 'lib_ico')).'.png" />',
            $name,
            $this->sl->scin->btn($lang[0],['attr'=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delLib/$new_id/$s/$id/$lib_id',function(){ $(this).sl('_tbl_del_tr') })"]]).
            $this->sl->scin->btn('&#8801;',['attr'=>['onclick'=>"$(this).sl('_promt',{input:['name'],load:['/ajax/{$this->modInfo[0]}/editLibVal/get/$new_id/name/$s/$id/$lib_id'],module:['/ajax/{$this->modInfo[0]}/editLibVal/set/$new_id/name/$s/$id/$lib_id','',function(i){ $(this).closest('tr').find('td:eq(1)').text(i[0].value) }],btn:{'$lang[1]':null}})"]]).
            $this->sl->scin->btn($lang[2],['attr'=>['onclick'=>($s >= 2 ? "$.buld('editObj','$new_id')" : "$.buld('editLib','$new_id','".($s+1)."','$lib_id')")]])
        ],$new_id);
        
    }
    function editLibVal($st,$id,$val,$s = 0,$parent_id = 0,$lib_id = 0){
        $s = intval($s);
        
        if($s == 1){
            $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$parent_id);
            
            if(!$lib) return;
            
            $name_val = $lib['category'][$id]['name'];
            
            if($st == 'set'){
                $lib['category'][$id]['name'] = strip_tags($_POST[0]);
                $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$parent_id,$lib);
            }
        }
        elseif($s == 2){
            $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id);
            
            if(!$lib) return;
            
            $name_val = $lib['category'][$parent_id]['obj'][$id]['name'];
            
            if($st == 'set'){
                $lib['category'][$parent_id]['obj'][$id]['name'] = strip_tags($_POST[0]);
                $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$lib_id,$lib);
            }
        }
        else{
            $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$id);
            
            if(!$lib) return;
            
            $name_val = $lib['name'];
            
            if($st == 'set'){
                $lib['name'] =  strip_tags($_POST[0]);
                $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$id,$lib);
            }
        } 
        
        return [['value'=>$name_val]];
    }
    function editLib($id = false,$s = 0,$lib_id = 0){
        $s = intval($s);
        
        $lang = $this->sl->fn->lang([
            'Назад',
            'Название',
            'Действия',
            'Удалить',
            'Сохранить',
            'Редактировать'
        ]);
        
        if($s > 0){
            $this->sl->scin->table_add_string($this->sl->scin->btn_group([
                $lang[0]=>"$.buld('editLib','$lib_id','".($s-1)."','$lib_id'); return false; l"
            ]));
        }
        
        $this->sl->scin->table_td_op(20,0,260);
        $this->sl->scin->table_head('',$lang[1],$lang[2]);
        $this->sl->scin->table_td_add_op([['class'=>'t_center t_bold dark'],['class'=>'t_bold']]);
        
        if($s == 1 || $s == 2){
            $lib_id = $s == 2 ? $lib_id : $id;
            
            $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id,'category');
            
            if($s == 2) $lib = $lib[$id]['obj'];
            
            foreach($lib as $cid=>$arr){
                $nar[] = array_merge($arr,['id'=>$cid]);
            }
        }
        else{
            $scan = $this->sl->fn->scan($this->BULD_LIB);
        
            foreach($scan['file'] as $file){
                $lib_id = str_replace('.data','',$file);
                $lib    = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id);
                
                if($lib) $nar[] = ['id'=>$lib_id,'name'=>$lib['name']];
            }
        }
        
        $this->sl->scin->table_dynamic([
            ['<img src="/modules/'.$this->modInfo[0].'/media/img/'.($s == 1 ? 'objects_ico' : ($s == 2 ? 'plus' : 'lib_ico')).'.png" />'],
            'name'=>function($v){
                return $v;
            }
        ],[
            $lang[3]=>[3=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delLib/{id}/$s/$id/$lib_id',function(){ $(this).sl('_tbl_del_tr') })"]],
            '&#8801;'=>[3=>['onclick'=>"$(this).sl('_promt',{input:['name'],load:['/ajax/{$this->modInfo[0]}/editLibVal/get/{id}/name/$s/$id/$lib_id'],module:['/ajax/{$this->modInfo[0]}/editLibVal/set/{id}/name/$s/$id/$lib_id','',function(i){ $(this).closest('tr').find('td:eq(1)').text(i[0].value) }],btn:{'$lang[4]':null}})"]],
            $lang[5]=>[3=>['onclick'=>($s >= 2 ? "$.buld('editObj','{id}')" : "$.buld('editLib','{id}','".($s+1)."','$lib_id')")]]
        ],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/addNewLib/'.$s.'/'.$id.'/'.$lib_id,1,false);
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        return $this->sl->scin->table_display();
    }
    private function allObj(){
        $scan = $this->sl->fn->scan($this->BULD_LIB);
        
        foreach($scan['file'] as $file){
            $lib_id = str_replace('.data','',$file);
            $lib    = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id);
            
            if($lib){
                foreach($lib['category'] as $cid=>$objs){
                    foreach($objs['obj'] as $objid=>$arrobj){
                        $obj[$objid] = [
                            'lib'=>$lib_id,
                            'cid'=>$cid,
                            'obj'=>$arrobj,
                            'code'=>@file_get_contents($this->BULD_LIB.DIR_SEP.'code'.DIR_SEP.$objid.'.data')
                        ];
                    }
                }
            }
        }
        
        return $obj ? $obj : [];
    }
    function editObj($id = 0,$save = false){
        
        $obj = $this->allObj();
        
        $lang = $this->sl->fn->lang([
            'Библиотеки не найдены',
            'Обьект не найден',
            'Название',
            'Действия',
            'Поле',
            'Список'
        ]);
        
        if(count($obj) == 0) $this->sl->fn->info($lang[0]);
        if(!$obj[$id]) $this->sl->fn->info($lang[1]);
        
        $lib = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$obj[$id]['lib']);
        
        $lib_obj = $lib['category'][$obj[$id]['cid']]['obj'][$id];
        
        if($save){
            if(isset($_POST['opObj']) && is_array($_POST['opObj'])){
            
                $opObj = $_POST['opObj'];
                
                foreach($opObj['name'] as $i=>$name){
                    $name = $this->sl->fn->replase(trim($name));
                    
                    if($name){
                        $op[$name] = [
                            'type'=>intval($opObj['type'][$i]),
                            'val'=>(intval($opObj['type'][$i]) == 2 ? intval($opObj['val'][$i]) : strip_tags($opObj['val'][$i]))
                        ];
                    }
                }
            }
            
            if($op) $lib['category'][$obj[$id]['cid']]['obj'][$id]['op'] = $op;
            
            @file_put_contents($this->BULD_LIB.DIR_SEP.'code'.DIR_SEP.$id.'.data',$_POST['code']);
            $this->sl->fn->conf('set',$this->BULD_LIB.DIR_SEP.$obj[$id]['lib'],$lib);
            
            return;
        }
        
        foreach($lib_obj['op'] as $i=>$arr){
            $nar[] = array_merge($arr,['id'=>$i]);
        }
        
        $this->sl->scin->table_td_op(16,120,60,0);
        $this->sl->scin->table_head('',$lang[2],$lang[3],'');
        $this->sl->scin->table_td_add_op([['class'=>'t_center t_bold dark'],['class'=>'t_bold']]);
        
        $this->sl->scin->table_dynamic([
            ['<img src="/modules/'.$this->modInfo[0].'/media/img/option_ico.png" />'],
            'id'=>function($v,$id,$row){
                return $this->sl->scin->input('opObj[name][]',$v).'<textarea name="opObj[val][]" style="display: none">'.$row['val'].'</textarea>';
            },
            'type'=>function($v) use($lang){
                return $this->sl->scin->select('opObj[type][]',[$lang[4],$lang[5],'Checkbox'],$v,['callback'=>'buld_addOpObjTypeSelect','attr'=>['onclick'=>"buld_addOpObjThisArea = $(this);"]]);
            }
        ],[
            '&#8776;'=>[3=>['onclick'=>"$(this).sl('_tbl_del_tr')"]]
        ],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row("buld_addOpObj",0,false);
        $this->sl->scin->table();
        
        $html = '
            <form method="POST" id="'.$this->modInfo[0].'_form_editObj">
            <div style="width: 38%; float: left;" class="win_h_size scrollbarInit">'.$this->sl->scin->table_display().'</div>
            <div style="width: 62%; float: left;" class="win_h_size scrollbarInit"><div class="win_h_size" minus="10"><textarea id="buldLibCodeMirror" style="opacity: 0; cursor: default" name="code"></textarea></div></div>
            </form>
            <script> var buldLibCodeMirror = '.json_encode([$obj[$id]['code'] ? $obj[$id]['code'] : '']).'; </script>
        ';
        
        return $html;
    }
    
    /**
     * Json Libraly
     */
     
    function jsLib(){
        $scan = $this->sl->fn->scan($this->BULD_LIB);
        
        foreach($scan['file'] as $file){
            $lib_id = str_replace('.data','',$file);
            $lib    = $this->sl->fn->conf('get',$this->BULD_LIB.DIR_SEP.$lib_id);
            
            if($lib) $nar[$lib_id] = $lib;
        }
        
        return $nar ? $nar : [];
    }
    
    /**
     * PRJ function
     */
    
    function openPrj($id){
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ,$id);
        $prj['save'] = count($prj['save']) == 0 ? false : $prj['save'];
        return $prj;
    }
    function savePrj($id){
        
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ);
        
        if(!$prj[$id]) $this->sl->fn->info($this->sl->fn->lang('Проект не найден или удален'));
        
        foreach($_POST['sort'] as $i=>$a) $sort[] = $a['value'];
        
        $json = json_decode($_POST['prj'],true);
        
        foreach($sort as $is) $sortObj[$is] = $json['save'][$is];
        
        $prj[$id]['save'] = $sortObj ? $sortObj : false;
        
        $this->sl->fn->conf('set',$this->BULD_PRJ,$prj);
        
    }
    private function comPrjValOne($hashID,$val){
        if(!$this->PRJSAVE[$hashID]) return '';
        if($this->OBJAttachValAR[$hashID][$val]) return $this->OBJAttachValAR[$hashID][$val];
        
        $objop = $this->PRJSAVE[$hashID]['values'][$val];
        
        if($objop['type'] >= 1) $var = intval($objop['value']);
        else{
            if(empty($objop['value'])) $var = $this->comPrjAthVal($objop['attach']);
            else $var = $objop['value'];
        }
        
        if($this->PRJOP['projected']){
            $sc  = $this->PRJOP['typeComent'] == 1 ? '<!--buld_'.$hashID.'_'.$val.'-->' : '/*buld_'.$hashID.'_'.$val.'*/';
            $ec  = $this->PRJOP['typeComent'] == 1 ? '<!--en_buld_'.$hashID.'_'.$val.'-->' : '/*en_buld_'.$hashID.'_'.$val.'*/';
            $var = $sc.$var.$ec;
        }
        
        $this->OBJAttachValAR[$hashID][$val] = $var;
        
        return $var;
    }
    private function comPrjValOp($hashID){
        $BULDVAR = [];
        
        foreach($this->PRJSAVE[$hashID]['values'] as $n=>$a) $BULDVAR[$n] = $this->comPrjValOne($hashID,$n);
        
        return $BULDVAR;
    }
    private function comPrjSortObj($ar){
        $preSort = $sort = [];
        
        foreach($ar as $ihas) $preSort[is_array($ihas) ? $ihas[0] : $ihas] = $ihas;
        
        foreach($this->PRJSAVE as $i=>$ob) if($preSort[$i]) $sort[] = $preSort[$i];
        
        return $sort;
    }
    private function comPrjAthVal($ar){
        $var     = '';
        
        $sort = $this->comPrjSortObj($ar);

        foreach($sort as $aobj){
            if(is_array($aobj)) $var .= $this->OBJAttachAR[$aobj[0]] = $this->comPrjValOne($aobj[0],$aobj[1]);
            else $var .=  $this->OBJAttachAR[$aobj] = $this->buidPrjCode($aobj);
        }
        
        return $var;
    }
    private function buidPrjCode($hashID,$nextHash = false,$val = false){
        if(!$this->PRJSAVE[$hashID]['op']['status']) return;
        
        $BULDVAR  = $this->comPrjValOp($hashID);
        $BULDCODE = $this->comPrjAth($hashID);
        
        @file_put_contents(FILE_PUT,$this->OBJALL[$this->PRJSAVE[$hashID]['op']['id']]['code']);
        
        ob_start();
        
        include(FILE_PUT);
        
        $BULDSTRING = ob_get_contents();
        
        ob_end_clean();
        
        return $BULDSTRING;
        
    }
    private function comPrjAth($hashID){
        if(!$this->PRJSAVE[$hashID]) return;
        
        if($this->OBJAttachAR[$hashID]) return $this->OBJAttachAR[$hashID];
        
        $var = '';
        
        $sort = $this->comPrjSortObj($this->PRJSAVE[$hashID]['op']['attach']);
        
        foreach($sort as $aobj){
            if(is_array($aobj)) $var .= $this->OBJAttachAR[$aobj[0]] = $this->comPrjValOne($aobj[0],$aobj[1]);
            else $var .= $this->OBJAttachAR[$aobj] = $this->buidPrjCode($aobj);
        }
        
        return $var;
    }
    function comPrj($id,$Projected = false){
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ,$id);
        
        if(!$prj) $this->sl->fn->info($this->sl->fn->lang('Проект не найден или удален'));
        
        $this->OBJALL = $this->allObj();
        $this->PRJSAVE = $prj['save'];
        $this->PRJOP = array_merge($prj['op'],['projected'=>$Projected]);
        $this->OBJAttachAR    = [];
        $this->OBJAttachValAR = [];
        
        $codeFull = '';

        foreach($this->PRJSAVE as $hashID=>$AROBJ){
            if($this->OBJAttachAR[$hashID]) continue;
            
            $codeFull .= $this->buidPrjCode($hashID);
        }
        
        @file_put_contents(SL_DIR.DIR_SEP.$prj['op']['pathFile'],$codeFull);
        
        if($Projected) $this->sl->fn->conf('set',$this->BULD_PRO.DIR_SEP.$id,$this->OBJAttachValAR);
    }
    private function parseComent($save,$op,$idPrj){
        
        $file  = @file_get_contents(SL_DIR.DIR_SEP.$op['pathFile']);
        $sepro = $this->sl->fn->conf('get',$this->BULD_PRO.DIR_SEP.$idPrj);
        
        $sc  = $op['typeComent'] == 1 ? '<!--buld_(\d+)_obj_([a-z0-9_]+)-->' : '/\*buld_(\d+)_obj_([a-z0-9_]+)\*/';
        
        $keySearch = $findChange = [];

        preg_match_all("'$sc'si",$file,$math);
        
        foreach($math[1] as $i=>$hashId){
            $keySearch[] = [$hashId.'_obj_'.$math[2][$i],$hashId,$math[2][$i]];
        }
        
        foreach($keySearch as $arrHash){
            $sr = $op['typeComent'] == 1 ? '<!--buld_'.$arrHash[0].'-->(.*?)<!--en_buld_'.$arrHash[0].'-->' : '/\*buld_'.$arrHash[0].'\*/(.*?)/\*en_buld_'.$arrHash[0].'\*/';
            
            preg_match("'$sr'si",$file,$mt);
            
            $se = $sepro[$arrHash[1].'_obj'][$arrHash[2]];
            
            $re = $op['typeComent'] == 1 ? '<!--buld_[a-z0-9_]+-->' : '/\*buld_[a-z0-9_]+\*/';
            
            $mt[1] = preg_replace("'$re'si",'',$mt[1]);
            $se    = preg_replace("'$re'si",'',$se);
            
            $re = $op['typeComent'] == 1 ? '<!--en_buld_[a-z0-9_]+-->' : '/\*en_buld_[a-z0-9_]+\*/';
            
            $mt[1] = preg_replace("'$re'si",'',$mt[1]);
            $se    = preg_replace("'$re'si",'',$se);
            
            if($se !== $mt[1]) $findChange[$arrHash[1].'_obj'] = [$arrHash[2],$mt[1]];
        }
        
        return $findChange;
    }
    function comProjectedJS(){
        $prjSearch = $_POST['prjSearch'];
        
        $json = json_decode($prjSearch,true);
        
        if(!$prjSearch || !$json) return [];
        
        $prj    = $this->sl->fn->conf('get',$this->BULD_PRJ);
        $bildCh = [];
        
        foreach($json as $idPrj=>$arr){
            if($prj[$idPrj]){
                $bildCh[$idPrj] = $this->parseComent($arr['save'],$prj[$idPrj]['op'],$idPrj);
            }
        }
        
        return $bildCh;
    }
    function delPrj($id){
        $this->sl->fn->conf('delete',$this->BULD_PRJ,$id);
    }
    function editPrjVal($st,$id,$val){
        
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ);
        
        if($st == 'set'){
            $prj[$id][$val] = strip_tags($_POST[0]);
            $this->sl->fn->conf('set',$this->BULD_PRJ,$prj);
        }
        
        return [['value'=>$prj[$id][$val]]];
    }
    function editPrj($id = false){
        
        $lang = $this->sl->fn->lang([
            'Название','Действия','Удалить','Сохранить','Настройки'
        ]);
        
        $this->sl->scin->table_td_op(20,0,230);
        $this->sl->scin->table_head('',$lang[0],$lang[1]);
        $this->sl->scin->table_td_add_op([['class'=>'t_center t_bold dark'],['class'=>'t_bold']]);
        
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ);
            
        foreach($prj as $cid=>$arr){
            $nar[] = array_merge($arr,['id'=>$cid]);
        }
        
        $this->sl->scin->table_dynamic([
            ['<img src="/modules/'.$this->modInfo[0].'/media/img/prj.png" />'],
            'name'=>function($v){
                return $v;
            }
        ],[
            $lang[2]=>[3=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delPrj/{id}',function(){ $(this).sl('_tbl_del_tr') })"]],
            '&#8801;'=>[3=>['onclick'=>"$(this).sl('_promt',{input:['name'],load:['/ajax/{$this->modInfo[0]}/editPrjVal/get/{id}/name'],module:['/ajax/{$this->modInfo[0]}/editPrjVal/set/{id}/name','',function(i){ $(this).closest('tr').find('td:eq(1)').text(i[0].value) }],btn:{'$lang[3]':null}})"]],
            $lang[4]=>[3=>['onclick'=>"$.buld('editPrjSettings','{id}')"]]
        ],$nar,[0,1000]);
        
        $this->sl->scin->table_dynamic_row($this->modInfo[0].'/addNewPrj',1,false);
        $this->sl->scin->table();
        $this->sl->scin->table_form();
        
        return $this->sl->scin->table_display();
    }
    function editPrjSettings($id,$set = false){
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ);
        
        $lang = $this->sl->fn->lang([
            'Проект не найден или удален',
            'Полный путь файла для компиляции',
            'Тип документа для комментариев'
        ]);
        
        if(!$prj[$id]) $this->sl->fn->info($lang[0]);
        
        if($set){
            $prj[$id]['op'] = [
                'pathFile'=>str_replace('"',"'",strip_tags($_POST['pathFile'])),
                'typeComent'=>intval($_POST['typeComent'])
            ];
            
            $this->sl->fn->conf('set',$this->BULD_PRJ,$prj);
            
            return;
        }
        
        $op = $prj[$id]['op'];
        
        $this->sl->scin->table_td_op(20,200);
        
        $this->sl->scin->table_tr([
            '<img src="/modules/'.$this->modInfo[0].'/media/img/option_ico.png" />',
            $this->sl->scin->input('pathFile',$op['pathFile']),
            $lang[1]
        ]);
        $this->sl->scin->table_tr([
            '<img src="/modules/'.$this->modInfo[0].'/media/img/option_ico.png" />',
            $this->sl->scin->select('typeComent',['php,css,js','html'],$op['typeComent']),
            $lang[2]
        ]);
        
        $this->sl->scin->table();
        $this->sl->scin->table_form('buld_form_prj_op');
        
        return $this->sl->scin->table_display();
    }
    function jMenuListPrj(){
        $prj = $this->sl->fn->conf('get',$this->BULD_PRJ);
            
        foreach($prj as $cid=>$arr) $nar[$cid] = $arr['name'];
        
        return $nar;
    }
    function addNewPrj(){
        if(!$this->ajaxLoad) return;
        
        $new_id = md5(time());
        
        $prj[$new_id] = [
            'name'=>'prj',
            'op'=>[],
            'save'=>[],
            'sort'=>[]
        ];
        
        $this->sl->fn->conf('update',$this->BULD_PRJ,$prj);
        
        $this->sl->scin->table_td_op([['class'=>'t_center t_bold dark'],['class'=>'t_bold']]);
        
        $lang = $this->sl->fn->lang([
            'Удалить',
            'Сохранить',
            'Настройки'
        ]);
        
        return $this->sl->scin->table_tr([
            '<img src="/modules/'.$this->modInfo[0].'/media/img/prj.png" />',
            'prj',
            $this->sl->scin->btn($lang[0],['attr'=>['onclick'=>"$(this).sl('_del_confirm','/ajax/{$this->modInfo[0]}/delPrj/$new_id',function(){ $(this).sl('_tbl_del_tr') })"]]).
            $this->sl->scin->btn('&#8801;',['attr'=>['onclick'=>"$(this).sl('_promt',{input:['name'],load:['/ajax/{$this->modInfo[0]}/editPrjVal/get/$new_id/name/'],module:['/ajax/{$this->modInfo[0]}/editPrjVal/set/$new_id/name','',function(i){ $(this).closest('tr').find('td:eq(1)').text(i[0].value) }],btn:{'$lang[1]':null}})"]]).
            $this->sl->scin->btn($lang[2],['attr'=>['onclick'=>"$.buld('editPrjSettings','$new_id')"]])
        ],$new_id);
    }
    
    /**
     * Show Buld Script
     */
     
    function show(){
        if($this->sl->fn->check_ac('admin')) return;
        
        $lang = $this->sl->fn->lang([
            'Сохранить',
            'Компилировать',
            'Компилировать проектируемый',
            'О скрипте',
            'Библиотека',
            'Проекты',
            'Открыть',
            'Слои',
            'Искать',
            'Свойства',
            'Новое значение',
            'Увеличить',
            'Уменьшить',
            'Перемещение объекта',
            'Проект не открыт',
            'Создание нового объекта',
            'значение',
            'Прорисовка линии',
            'Удаление объекта',
            'Прикрепить',
            'Удалить',
            'Настройки',
            'Обьект',
            'Автор скрипта',
            'Версия',
            'О скрипте',
            'Укажите 0 или 1',
            'Поле',
            'Список'
        ]);
        
        $menu = $this->sl->scin->btn_group([
            $lang[0]=>"$.buld('savePrj'); return false; l",
            $lang[1]=>"$.buld('comPrj'); return false; l",
            $lang[2]=>"$.buld('comPrjProjected'); return false; l",
            $lang[3]=>"$.buld('buldInfo'); return false; l"
        ]);
        
        ob_start();
        
        include __DIR__.DIR_SEP.'inc'.DIR_SEP.'buld.php';
        
        $buld = ob_get_contents();
        
        ob_end_clean();
        
        return $this->sl->scin->css('inc','/modules/'.$this->modInfo[0].'/media').'<script type="text/javascript" src="/modules/'.$this->modInfo[0].'/inc/lib.js.php"></script>'.$menu.$buld.$this->sl->scin->cache_js(__DIR__,['lang'=>json_encode($lang)]).'<script>$.buld();</script>';
    }
}
?>