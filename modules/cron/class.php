<?
/**
 * @cron
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class cron{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->microtime = microtime(true);
    }
    private function times(){
        $ex_micro = explode(' ',microtime());
        return '['.date('m',time()).':'.date('s',time()).':'.$ex_micro[0].']';
    }
    function start($name){
        if(strstr($name,DIR_SEP)){
            $ex = explode(DIR_SEP,$name);
            $end = explode('.',end($ex));
            $name = $end[0];
        }
        $this->croname = $name;
        $dir = SL_DIR.'/cron/cache/'.$name;
        $scan = $this->sl->fn->scan($dir);
        
        foreach($scan['file'] as $filename){
            $ex = explode('_',$filename);
            if(time() > $ex[0]+60*60) @unlink($dir.DIR_SEP.$filename);
        }
        
        $this->sl->fn->conf('update',SL_DIR.'/cron/data/'.$name,['time'=>time(),'status'=>1]);
        @file_put_contents(SL_DIR.'/cron/result/'.$name.'.data','');
    }
    function stop(){
        $this->sl->fn->conf('update',SL_DIR.'/cron/data/'.$this->croname,['time'=>time(),'status'=>0]);
    }
    function display($result,$data = false){
        $time = explode(' ',microtime());
        @mkdir(SL_DIR.'/cron/cache/'.$this->croname);
        if($data) @file_put_contents(SL_DIR.'/cron/cache/'.$this->croname.'/'.$time[1].'_'.$time[0],$data);
        @file_put_contents(SL_DIR.'/cron/result/'.$this->croname.'.data',$this->times().' '.$result.($data ? ' <b onclick="$.sl(\'load\',\'/ajax/'.$this->modInfo[0].'/show_cache/'.$this->croname.'/'.$time[1].'_'.$time[0].'\',function(d){ $.sl(\'window\',{name:\'cache_cron\',data:d,drag:true,size:true,resize:true,bg:false,w:500,h:300,title:\'Cache result\'}) })" class="t_point t_shadow">[wath]</b>' : '')."\n",FILE_APPEND);
    }
    function show_cache($name,$file){
        return '<div class="t_p_10">'.@file_get_contents(SL_DIR.'/cron/cache/'.$name.'/'.$file).'</div>';
    }
    function time_exe($limit_sec){
        $exec_time = round(microtime(true) - $this->microtime);
        
        if($exec_time > $limit_sec) return true;
        else return false;
    }
    function delete($id = false){
        @unlink(SL_DIR.'/cron/data/'.$id.'.conf');
        @unlink(SL_DIR.'/cron/result/'.$id.'.data');
        $this->sl->fn->del_dir(SL_DIR.'/cron/cache/'.$id.'/');
    }
    function show_result($name){
        $ex = explode("\n",@file_get_contents(SL_DIR.'/cron/result/'.$name.'.data'));
        return '<div class="t_p_10">'.implode("<br />",$ex).'</div>';
    }
    function show($page = 1,$like = false){
        $scan = $this->sl->fn->scan(SL_DIR.'/cron/data');
        
        foreach($scan['file'] as $file){
            $name = str_replace(".conf",'',$file);
            $this->sl->fn->conf('set',SL_DIR.'/cron/data/'.$name,['time'=>time(),'status'=>1]);
            $conf = $this->sl->fn->conf('get',SL_DIR.'/cron/data/'.$name);
            
            
            if(time() > intval($conf['time'])*60){
                @unlink(SL_DIR.'/cron/data/'.$file);
                continue;
            }
            
            $nar[$name] = array_merge(['id'=>$name],$conf);
        }
        
        $this->sl->scin->table_add_string('<div class="sep"></div>');
        
        $this->sl->scin->table_td_op(20,170,0,150);
        
        $this->sl->scin->table_head('','Название','Инфо','');
        
        $this->sl->scin->table_td_op([1=>['class'=>'light'],2=>['class'=>'t_center t_bold'],3=>['class'=>' t_center t_bold']]);
        
        $this->sl->scin->table_dynamic([
            'status'=>function($v){
                return $v == 1 ? '<img src="/icons/ok.png" />' : '<img src="/icons/loading.gif" />';
            },
            'id'=>['<b>','</b>'],
            []
            
        ],[
            'Удалить'=>["/ajax/{$this->modInfo[0]}/delete"],
            '&#8801;'=>[3=>['tip'=>'Консоль','onclick'=>"{$this->modInfo[0]}_bindWin('{id}')"]]
        ],$nar,[0-100]);
        
        $this->sl->scin->table(['class'=>'text_shadow']);
        $this->sl->scin->table_form();

        return $this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__);
    }
}
?>