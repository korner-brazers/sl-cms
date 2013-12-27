<?
/**
 * @update_modules
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class update_modules{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
        $this->market_id = SL_DATA.DIR_SEP.'market_id';
    }
    function show(){
        if($this->sl->fn->check_ac('root')) return;
        
        if(!$this->sl->market()) $this->sl->fn->info('Модуль ( market ) не установлен или не найден');
        
        $mods = $this->sl->fn->conf('get',$this->market_id);

        $this->sl->scin->table_td_op(50,0,100,100);
        $this->sl->scin->table_head('','Название','','Действие');
        $this->sl->scin->table_td_add_op([2=>['class'=>'t_center dark'],['class'=>'t_center light']]);
        
        if(count($mods) > 0) $nar = $this->sl->fn->server('conect/update_modules',['POST'=>http_build_query(['ids'=>$mods])]);
        else $nar = [];
        
        $this->sl->scin->table_dynamic([
            'ico'=>function($v){
                return  '<img src="http://sl-cms.com/upload/market/ico/'.$v.'" style="border-radius: 3px 3px 3px 3px; overflow: hidden;" />';
            },
            'name'=>function($v,$id,$r){
                return  '<b><a href="http://sl-cms.com/sl_market/id/'.$r['id'].'" class="t_color_w t_shadow" style="text-decoration: none" target="_blank">'.$v.'</a></b><br />'.$r['descr'];
            },
            function($d){
                return  '<img src="/modules/'.$this->modInfo[0].'/images/i.gif" class="load" style="border-radius: 3px 3px 3px 3px; overflow: hidden; display: none" /><img src="/modules/'.$this->modInfo[0].'/images/ok.png" class="ok" style="display: none" tip="Установка успешна" /><img src="/modules/'.$this->modInfo[0].'/images/error.png" class="error" style="display: none" tip="Ошибка установки" />';
            },
            'ver'=>function($v,$id,$r) use($mods){
                return  $v > $mods[$r['id']] ? $this->sl->scin->btn('Обновить',['attr'=>['onclick'=>$this->modInfo[0].'.apply(this,['.$r['id'].'])']]) : 'Нет обновлений';
            }
        ],[],$nar,[0,1000]);
        
        $this->sl->scin->table($this->modInfo[0].'_table');
        
        return $this->sl->scin->table_display().$this->sl->scin->cache_js(__DIR__);
    }
}
?>