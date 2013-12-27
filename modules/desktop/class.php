<?
/**
 * @desktop
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
class desktop{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        
        $this->sl->auth->check_member();
        
        $this->user = $this->sl->auth->member_id['login'] ? $this->sl->auth->member_id['login'] : 'root';
    }
    function __call($class, $params = false) {
        
    }
    private function cr_dir(){
        @mkdir(SL_DIR.'/cache/desktop/');
    }
    function update($name,$x,$y,$title,$type,$ico){
        if($this->sl->fn->check_ac('user')) return;
        
        $this->cr_dir();
        
        $name = $this->sl->fn->replase($name);
        $x = intval($x);
        $y = intval($y);
        $ico = intval($ico);
        $title = urldecode($title);
        
        $this->sl->fn->conf('update',SL_DIR.'/cache/desktop/'.$this->user,[$name=>['title'=>$title,'x'=>$x,'y'=>$y,'type'=>$type,'ico_img'=>$ico]]);
    }
    function delete($name){
        if($this->sl->fn->check_ac('user')) return;
        
        $this->sl->fn->conf('delete',SL_DIR.'/cache/desktop/'.$this->user,$this->sl->fn->replase($name));
    }
    function get(){
        if($this->sl->fn->check_ac('user')) return;
        
        return $this->sl->fn->conf('get',SL_DIR.'/cache/desktop/'.$this->user);
    }
    private function create_blur(){
        if(!file_exists(SL_UPLOAD.'/admin_bg/blur.jpg')){
            $img = @imagecreatefromjpeg(SL_UPLOAD.'/admin_bg/default.jpg');
            
            for($i = 0;$i < 10; $i++){
                @imagefilter($img,IMG_FILTER_GAUSSIAN_BLUR);
            }
        
            if(function_exists('imageconvolution')) @imageconvolution($img, [[1.0, 2.0, 4.0],[2.0, 4.0, 2.0],[1.0, 2.0, 1.0]], 17, 0);
            
            @imagejpeg($img, SL_UPLOAD.'/admin_bg/blur.jpg', 100);
            @imagedestroy($img);
        }
    }
    function show(){
        if($this->modInfo[4]) return;
        
        if(defined('ADMINFILE')){
            $this->create_blur();
            
            include __DIR__.DIR_SEP.'show.php';
        }
    }
}
?>