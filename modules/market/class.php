<?
/**
 * @market
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class market{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function __call($method,$arr){
        
    }
    function install_module($id = 0){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $id = intval($id);
        
        $json = $this->sl->fn->server('conect/download_module/'.$id);
        
        $name_zip = SL_CACHE.DIR_SEP.'upload_module_sl_market.zip';
        
        @unlink($name_zip);
        
        @copy('http://sl-cms.com/upload/market/zip/'.$id.'_'.$json['zip'].'.zip',$name_zip);
        
        if(file_exists($name_zip)){
            include_once SL_PLUGINS.DIR_SEP.'pclzip.lib.php';
        
            $archive = new PclZip( $name_zip );
            
            if($json['type'] == 0) $dir_extract = 'modules/';
            elseif($json['type'] == 1) $dir_extract = '';
            elseif($json['type'] == 2) $dir_extract = 'tpl/';
            
            if($archive->extract($dir_extract) == 0) $this->sl->fn->info('Не удалось извлечь архив! '.$archive->errorInfo(true));
            else{
                $ids = $this->sl->fn->conf('get',SL_DATA.DIR_SEP.'market_id');
                
                $ids[intval($json['id'])] = $json['ver'];
                
                $this->sl->fn->conf('set',SL_DATA.DIR_SEP.'market_id',$ids);
                
                return ['success'=>true];
            }
    
        }
        else $this->sl->fn->info('Архив не загружен');
    }
    function get_install($id = 0){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $id = intval($id);
        
        $string  = '<div class="install_box_install"><div class="animate"></div>Установка</div>';
        $string .= '<div class="install_box_install" style="display:none"><div class="success"></div>Установка завершена</div>';
        $string .= '<div class="install_box_install error" style="display:none"><div class="error"></div>Ощибка установки</div>';
        
        return $string.$this->sl->scin->cache_css(__DIR__).$this->sl->scin->cache_js(__DIR__).'<script>'.$this->modInfo[0].'_get_install('.$id.')</script>';
    }
    function show(){
        if($this->sl->fn->check_ac('admin')) return;
        return '<iframe src="http://sl-cms.com/sl_market/show/'.$_SERVER["HTTP_HOST"].'" class="shell_iframe"></iframe>';
    }
}
?>