<?
/**
 * @scan
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class scan{
    private $scanFiles = [];
    
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->confScanList = SL_CACHE.DIR_SEP.'list_'.$this->modInfo[0];
        $this->confScanRe   = SL_CACHE.DIR_SEP.'result_'.$this->modInfo[0];
        
        mkdir(SL_DATA.DIR_SEP.'scan');
    }
    function getFolder($set = false,$ar = false){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('root')) return;
        
        $l = file_get_contents($this->confScanList);
        
        if($set) file_put_contents($this->confScanList,strip_tags($_POST['area']));
        
        $ex = explode("\n",trim($l));
        $str = [];
        
        foreach($ex as $line){
            $line = preg_replace("'[^a-z0-9\.\-\_\/\\\]'si",'',trim($line));
            $line = str_replace(['\/','/'],DIR_SEP,trim($line));
            $line = str_replace(DIR_SEP.DIR_SEP,DIR_SEP,$line);
            
            $line = $line[0] == DIR_SEP ? substr($line,1) : $line;
            $line = $line[strlen($line)-1] !== DIR_SEP ? $line.DIR_SEP : $line;
            
            if(realpath($line) && $line !== DIR_SEP) $str[] = str_replace(SL_DIR.DIR_SEP,'',realpath($line));
        }
        
        return $ar ? $str : implode("\n",$str);
    }
    private function whileScan($dir){
        $scan = $this->sl->fn->scan($dir);
        
        foreach($scan['file'] as $file){
            $this->scanFiles[str_replace(SL_DIR,'',$dir.$file)] = filemtime($dir.$file);
        }
        foreach($scan['dir'] as $folder){
            $this->whileScan($dir.$folder.DIR_SEP);
        }
    }
    function fullScan($nosave = false){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('root')) return;
        
        $dirs = $this->getFolder(false,true);
        
        foreach($dirs as $f) $this->whileScan(SL_DIR.DIR_SEP.$f.DIR_SEP);
        
        if(!$nosave) $this->sl->fn->conf('set',$this->confScanRe,$this->scanFiles);
    }
    private function showS($list){
        
        $lang = $this->sl->fn->lang([
            'Файл','Размер','Дата'
        ]);
        
        $this->sl->scin->table_clear();
        
        $this->sl->scin->table_td_op(0,160,220);
        $this->sl->scin->table_td_add_op([['class'=>'t_bold'],['class'=>'t_center light'],['class'=>'t_center']]);
        $this->sl->scin->table_head($lang[0],$lang[1],$lang[2]);
        
        foreach($list as $file){
            $f_v = str_replace(['\/','/'],DIR_SEP,trim($file));
            $f_v = explode(DIR_SEP,$f_v);
            $f_n = [];
            
            foreach($f_v as $g) if($g != '') $f_n[] = '<span class="plf"><span style="color: #ff6c00">'.$this->sl->fn->substr($g,0,1,'').'</span>'.$this->sl->fn->substr($g,1,100).'</span>';
            
            $this->sl->scin->table_tr([
                implode(DIR_SEP,$f_n),
                $this->sl->fn->filesize(SL_DIR.$file,true),
                $this->sl->fn->rus_date('l, j F Y H:i',filemtime(SL_DIR.$file))
            ]);
        }
        
        $this->sl->scin->table();
        
        return $this->sl->scin->table_display();
    }
    function backup(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('root')) return;
        
        $lang = $this->sl->fn->lang([
            'Файл','Размер','Дата','Восстановить','Удалить'
        ]);
        
        $this->sl->scin->table_td_op(0,90,220,60,60);
        $this->sl->scin->table_td_add_op([['class'=>'t_bold'],['class'=>'t_center light'],['class'=>'t_center']]);
        $this->sl->scin->table_head($lang[0],$lang[1],$lang[2],'','');
        
        $dir = SL_DATA.DIR_SEP.'scan'.DIR_SEP;
        
        $list = $this->sl->fn->scan($dir);
        
        foreach($list['file'] as $f){
            $this->sl->scin->table_tr([
                $f,
                $this->sl->fn->filesize($dir.$f,true),
                $this->sl->fn->rus_date('l, j F Y H:i',filemtime($dir.$f)),
                $this->sl->scin->btn($lang[3],['attr'=>['onclick'=>"{$this->modInfo[0]}_restoreBackup('$f')"]]),
                $this->sl->scin->btn($lang[4],['attr'=>['onclick'=>"{$this->modInfo[0]}_restoreBackup('$f',true,this)"]])
            ]);
        }
        
        $this->sl->scin->table();
        
        return $this->sl->scin->table_display();
    }
    function createBackup(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('root')) return;
        
        $this->fullScan();
        
        require_once(SL_PLUGINS.DIR_SEP.'pclzip.lib.php');
        
        $archive = new PclZip(SL_DATA.DIR_SEP.'scan'.DIR_SEP.date('d.m.Y_').time().'.zip');
        
        foreach($this->scanFiles as $f=>$t) $path[] = SL_DIR.$f;
        
        if($path) $archive->create($path,PCLZIP_OPT_REMOVE_PATH, SL_DIR);
        
        return $this->backup();
    }
    function createBackupChange(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('root')) return;
        
        $scan = $this->scanAllShow();
        
        $path = [];
        
        require_once(SL_PLUGINS.DIR_SEP.'pclzip.lib.php');
        
        $archive = new PclZip(SL_DATA.DIR_SEP.'scan'.DIR_SEP.date('d.m.Y_').'_change_'.time().'.zip');
        
        foreach($scan[0] as $fi) $path[] = SL_DIR.$fi;
        foreach($scan[1] as $fi) $path[] = SL_DIR.$fi;
        
        if($path) $archive->create($path,PCLZIP_OPT_REMOVE_PATH, SL_DIR);
        
        return $this->backup();
    }
    function restoreBackup($delete = false){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('root')) return;
        
        $id = $_POST['id'];
        
        if($delete){
            unlink(SL_DATA.DIR_SEP.'scan'.DIR_SEP.$id); return;
        }
        
        $lang = $this->sl->fn->lang([
            'Бэкап не найден','Не удалось извлечь архив','Восстановление прошло успешно'
        ]);
        
        $this->fullScan();
        
        if(!file_exists(SL_DATA.DIR_SEP.'scan'.DIR_SEP.$id)) $this->sl->fn->info($lang[0]);
        
        require_once(SL_PLUGINS.DIR_SEP.'pclzip.lib.php');
        
        $archive = new PclZip(SL_DATA.DIR_SEP.'scan'.DIR_SEP.$id);
        
        if($archive->extract('') == 0) $this->sl->fn->info($lang[1].'! '.$archive->errorInfo(true));
        else{
            $this->sl->fn->info($lang[2].'!');
        }
    }
    private function scanAllShow(){
        $scan = $this->sl->fn->conf('get',$this->confScanRe);
        
        $change = $delete = $new = [];
        
        foreach($scan as $l=>$i){
            if(file_exists(SL_DIR.$l)){
                $newTime = filemtime(SL_DIR.$l);
            
                if($newTime > $i) $change[] = $l;
            }
            else $delete[] = $l;
        }
        
        $this->fullScan(true);
        
        foreach($this->scanFiles as $f=>$t){
            if(!$scan[$f]) $new[] = $f;
        }
        
        return [$change,$new,$delete];
    }
    function show($name = ''){
        if($this->sl->fn->check_ac('root')) return;
        
        $btn = $this->sl->scin->floating($this->modInfo[0].'_menu()');
        
        $scan = $this->scanAllShow();
        
        $style = '<style>
            span.plf{
                background: none repeat scroll 0 0 #0F0F0F;
                border-radius: 3px 3px 3px 3px;
                display: inline-block;
                font-style: normal;
                font-weight: bold;
                line-height: 18px;
                margin: 3px 0;
                padding: 1px 5px;
                text-transform: none;
            }
        </style>';
        
        $lang = $this->sl->fn->lang([
            'Изменены','Новые','Удалены',
            'Вы действительно хотите это сделать',
            'Выбор папок',
            'Настройка папок для сканирования',
            'Сканировать',
            'Сканировать все папки и файлы',
            'Бэкап',
            'Восстановление резервной копии из бэкапа',
            'Выберите опцию',
            'Сохранить',
            'Новое сканирование произведет к полному обновлению записи, вы действительно хотите произвести сканирование',
            'Создать бэкап',
            'Все папки',
            'Бэкап всех папок указанных в настройках',
            'Частичный',
            'Бэкап файлов которые подверглись изменению и добавлены новые файлы',
            'Создания бэкапа',
            'Папки',
            'Предупреждение'
        ]);
        
        return $style.$this->sl->scin->slide([
            $lang[0]=>$this->showS($scan[0]),
            $lang[1]=>$this->showS($scan[1]),
            $lang[2]=>$this->showS($scan[2])
        ]).$btn.$this->sl->scin->cache_js(__DIR__,['lang'=>json_encode($lang)]);
    }
}
?>