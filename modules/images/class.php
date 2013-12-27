<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class images{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    private function get_extension($file_name){
    	$ext = explode('.', $file_name);
    	$ext = array_pop($ext);
    	return strtolower($ext);
    }
    private function check_connect(){
        if(!$this->sl->db->connect(false)) $this->sl->fn->info('Нет соединения с базой данных');
                
        $this->sl->fn->check_ac('admin');
    }
    function attach($id,$cid,$tbl = ''){
        if(!$this->ajaxLoad) return;
        $this->check_connect();
                
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        $this->sl->db->update($tbl,['cid'=>intval($cid)],intval($id));
    }
    function create_bg_admin($id,$tbl = ''){
        if(!$this->ajaxLoad) return;
        $this->check_connect();
                
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $img = $this->sl->db->select($tbl,$id);
        
        $ex = $this->get_extension($img['big']);
        
        if($ex !== 'jpg') $this->sl->fn->info('Только изображения формата (jpg)');
        
        @copy(SL_UPLOAD.DIR_SEP.'images'.DIR_SEP.$img['big'],SL_UPLOAD.DIR_SEP.'admin_bg/default.jpg');
        @unlink(SL_UPLOAD.DIR_SEP.'admin_bg/blur.jpg');
    }
    function upload_img($cid = 0,$tbl = ''){
        if(!$this->ajaxLoad) return;
        
        if(!$this->sl->db->connect(false)) $this->sl->fn->info('Нет соединения с базой данных');
        
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $upload_id = SL_UPLOAD.DIR_SEP.'images';
        
        @mkdir($upload_id);
        
        $upload_dir  = $upload_id.DIR_SEP;
        
        $allowed_ext = array('jpg','png');
        
        if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
        	$this->sl->fn->info('Error! Wrong HTTP method!');
        }
        
        if($this->sl->auth->member_id['admin_ac']){
            $this->sl->db->alterTableAdd('images',[
                'small'=>['VARCHAR','100',''],
                'big'=>['VARCHAR','100',''],
                'name'=>['VARCHAR','100','']
            ]);
        }
        
        if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
        	
        	$pic = $_FILES['pic'];
            
            $ex = $this->get_extension($pic['name']);
            
        	if(!in_array($ex,$allowed_ext)){
        		$this->sl->fn->info('Only '.implode(',',$allowed_ext).' files are allowed!');
        	}
            
            $name = $pic['name'];
            $md5 = md5(time().$pic['name']);
            $md5_ex = $md5.'.'.$ex;
            
        	if(move_uploaded_file($pic['tmp_name'], $upload_dir.$md5_ex)){
        	   $this->sl->crop_img->dir = $upload_dir;
               
               $info_img = getimagesize($upload_dir.$md5_ex);
               
               if($info_img[0] > 210){
                    $result_small = $this->sl->crop_img->size($md5_ex,210,130);
                    
                    $info_img = getimagesize($upload_dir.$result_small);
               }
               else $result_small = $md5_ex;
               
               $this->sl->db->insert($tbl,['small'=>$result_small,'big'=>$md5_ex,'cid'=>intval($cid),'name'=>$name]);
               
        	   return ['id'=>$this->sl->db->insert_id(),'src_small'=>'/upload/images/'.$result_small,'src_big'=>'/upload/images/'.$md5_ex,'name'=>$md5_ex,'w'=>$info_img[0],'h'=>$info_img[1]];
        	}
        	
        }
        
        $this->sl->fn->info('Что-то пошло не так с вашей загрузкой!');
        
    }
    function delete($id,$tbl = false,$checkTable = false,$cid = false){
        
        if(!$this->ajaxLoad) return;
        
        
        
        if($checkTable && $cid) {
            if(!$this->sl->db->connect(false)) $this->sl->fn->info('Нет соединения с базой данных');
            
            $this->sl->fn->check_ac('user');
            
            if($this->sl->db->count($checkTable,'id='.$id.' AND '.$this->sl->fn->replase($cid).'='.$this->sl->auth->member_id['id']) == 0) $this->sl->fn->info('Ошибка данных (images) не найдена запись');
        }
        else $this->check_connect();
        
        $id = intval($id);
        
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $images = $this->sl->db->select($tbl,$id);
        
        @unlink(SL_UPLOAD.DIR_SEP.'images'.DIR_SEP.$images['small']);
        @unlink(SL_UPLOAD.DIR_SEP.'images'.DIR_SEP.$images['big']);
        
        $this->sl->db->delete($tbl,$id);
    }
    function delete_all($one = false,$two = false){
        if(!$this->ajaxLoad) return;
        
        $this->check_connect();
        
        $showme = $this->sl->fn->showme($one,$two,'images');
        
        $tbl   = $showme[2][1];
        $id    = $showme[2][0];
        
        $this->sl->db->select($tbl,'cid='.$id,false);
        
        $this->sl->db->get_while(function($row){
            @unlink(SL_UPLOAD.DIR_SEP.'images'.DIR_SEP.$images['small']);
            @unlink(SL_UPLOAD.DIR_SEP.'images'.DIR_SEP.$images['big']);
        });
        
        $this->sl->db->delete($tbl,'cid='.$id,false);
    }
    function id($id,$tbl = '',$type,$w = 0,$h = 0,$desc = 0){
        if(!$this->sl->db->connect(false)) return;
        
        $id = intval($id);
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $upload_dir = SL_UPLOAD.DIR_SEP.'images';
        
        $image = $this->sl->db->select($tbl,$id);
        
        if(!$image) return;
        
        $ex = $this->get_extension($image['big']);
        
        if($ex == 'jpg') header('Content-Type: image/jpeg');
        elseif($ex == 'png') header('Content-Type: image/png');
        
        if($type == 'original') $img = file_get_contents($upload_dir.DIR_SEP.$image['big']);
        elseif($type == 'size'){
            $this->sl->crop_img->dir = $upload_dir;
            
            if($w == 0 || $h == 0) return;
            
            $result_name = $this->sl->crop_img->size($image['big'],$w,$h);
            
            $img = file_get_contents($upload_dir.DIR_SEP.$result_name);
        }
        
        return $img;
    }
    function name($id,$tbl = ''){
        if(!$this->sl->db->connect(false)) return;
        
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $upload_dir = SL_UPLOAD.DIR_SEP.'images';
        
        $image = $this->sl->db->select($tbl,intval($id));
        
        if(!$image) return;
        
        return $image['big'];
    }
    function get_while($callback,$cid,$tbl = '',$limit = 0){
        
        if($this->modInfo[5]) return;
        if($this->ajaxLoad) return;
        
        $cid = intval($cid);
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $query_id = $this->sl->db->select($tbl,['WHERE'=>'cid='.$cid,'LIMIT'=>$limit,'ORDER'=>'id DESC']);
        
        while($row = $this->sl->db->get_row($query_id)){
            if(is_callable($callback)) $callback($row);
        }
    }
    function show_img($cid,$tbl = '',$type = 'original',$w = 0,$h = 0){
        
        $cid = intval($cid);
        $tbl = empty($tbl) ? 'images' : 'images_'.$tbl;
        
        $img = $this->sl->db->get_row($this->sl->db->select($tbl,'cid='.$cid));
        
        if($img){
            if($type == 'original') $return_name = $img['big'];
            elseif($type == 'size') $return_name = $this->sl->crop_img->size($img['big'],$w,$h);
            
            if($return_name) return '<img src="/upload/images/'.$return_name.'" class="img" />';
        }
    }
    function show($one = false,$two = false,$page = 1){
        if(!$this->sl->db->connect(false)){
            if($this->ajaxLoad) $this->sl->fn->info('Нет соединения с базой данных');
            else return $this->sl->stpl->mysql_error_connect('');
        } 
        
        $showme = $this->sl->fn->showme($one,$two,'images');
        
        $tbl   = $showme[2][1];
        $tbln  = $showme[2][2];
        $id    = $showme[2][0];
        $lim   = intval($showme[1][1]) > 0 ? intval($showme[1][1]) : 19;
        $count = 0;
        
        $this->sl->auth->check_member();
        
        if($showme[1][1] && $showme[1][2]){
            if($this->sl->db->count($showme[1][1],'id='.intval($id).' AND '.$this->sl->fn->replase($showme[1][2]).'='.$this->sl->auth->member_id['id']) == 0){
                if($this->ajaxLoad) $this->sl->fn->info('Ошибка данных (images) не найдена запись');
                else return 'Ошибка данных (images) не найдена запись';
                
                $myImg = true;
            } 
        }
        
        if($this->sl->auth->member_id['admin_ac']){
            $this->sl->db->alterTableAdd($tbl,[
                'small'=>['VARCHAR','100',''],
                'big'=>['VARCHAR','100',''],
                'name'=>['VARCHAR','100','']
            ]);
        }
        
        $this->string  = $this->sl->scin->cache_js(__DIR__);
        $this->string .= $this->sl->scin->cache_css(__DIR__);
        
        $this->string .= '
                    <div id="image_box_prew" class="image_box t_p_f t_left t_width t_height">
                        <div class="t_p_r"><div class="close_box image_box_close_prew t_animate t_p_a t_left t_top t_width" style="z-index: 40"></div></div>
                        <div class="box_conteiner scrollbarInit"></div>
                    </div>
                    
                    <div class="t_p_30 t_clearfix" id="image_box">
                        <ul id="box_list" class="t_ul">';
        
        if($this->sl->auth->member_id['admin_ac'] || $myImg){
            $this->string .= '<li id="dropbox">
                            <span class="message">Drop images here to upload. <br /><i>(they will only be visible to you)</i></span>
                        </li>';
        }
        
    
        $images = $this->sl->db->select($tbl,['LIMIT'=>[$page,$lim],'ORDER'=>'id DESC','WHERE'=>($id ? 'cid='.$id : false)]);
        $count  = $this->sl->db->count($tbl,($id ? 'cid='.$id : false));
        
        $this->sl->db->get_while(function($row){
            
            $info_img = @getimagesize(SL_UPLOAD.DIR_SEP.'images'.DIR_SEP.$row['small']);
            
            $this->string .= '<li class="preview done" id="'.$row['id'].'">
                    <div class="view-img" rel="/upload/images/'.$row['big'].'">
                        <img src="/upload/images/'.$row['small'].'" class="img" style="margin-left: '.((210 - $info_img[0]) / 2).'px; margin-top: '.((128 - $info_img[1]) / 2).'px" />
                    </div>
        			<div class="progressHolder">
        				<div class="progress"></div>
        			</div>
                    '.($this->sl->auth->member_id['admin_ac'] || $myImg ? '<div class="edit_ico"></div>' : '').'
                    '.($this->sl->auth->member_id['admin_ac'] ? '<div class="cid">'.$row['cid'].'</div>' : '').'
                </li>';
            
        },$images);
        
        
        
        $this->string .= '</ul></div>';
        
        if($this->ajaxLoad) $this->string .= $this->sl->nav->show($count,$lim,$page,"$.sl('shell',{name:'{$this->modInfo[0]}',add_param:'$id/$tbln/{n}'},'update')",1);
        else $this->string .= $this->sl->nav->show($count,$lim,$page,'/'.$this->modInfo[0].'/show/'.$id.'/'.$tbln.'/{n}');
        
        return $this->string.'<script>init_dropbox = false; init_dropbox_id = '.$id.'; init_dropbox_tbl = \''.$tbln.'\'; '.($this->sl->auth->member_id['admin_ac'] ? 'init_dropbox_admin = true;' : '').'</script>';
    }
}
?>