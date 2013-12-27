<?
/**
 * @settings
 * @author korner
 * @copyright SL-SYSTEM 2012
 */
 
class dle_tpl{
    function init($sl,$moduleInfo = [],$ajaxLoad = false){
        $this->sl = $sl;
        $this->modInfo  = $moduleInfo;
        $this->ajaxLoad = $ajaxLoad;
    }
    function show_list(){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $scan = $this->sl->fn->scan(SL_DIR.DIR_SEP.'tpl'.DIR_SEP);
        $list = [];
        
        foreach($scan['dir'] as $name){
            $list[] = [$name,''];
        }
        
        return $list;
    }
    private function include_parse($name){
        return file_get_contents(SL_DIR.DIR_SEP.'tpl'.DIR_SEP.$this->tpl.DIR_SEP.$name);
    }
    private function group_parse($str){
        $str = preg_replace("'\[group=5\](.*?)\[\/group\]'si","<? if(!\$sl->auth->member_id){ ?>\\1<? } ?>",$str);
        
        $str = preg_replace("'\[not-group=5\](.*?)\[\/not-group\]'si","<? if(\$sl->auth->member_id){ ?>\\1<? } ?>",$str);
        $str = preg_replace("'\[not-group=[0-9]\](.*?)\[\/not-group\]'si","\\1",$str);
        
        $str = preg_replace("'\[group=[1-9]\](.*?)\[\/group\]'si","\\1",$str);
        
        return $str;
    }
    private function parse_tag($str){
        $str = preg_replace("'\[category=.*?\](.*?)\[\/category\]'si","\\1",$str);
        $str = preg_replace("'\[not-category=.*?\](.*?)\[\/not-category\]'si","\\1",$str);
        
        return $str;
    }
    private function parse_news($str){
        
        $str = preg_replace("'\{include file=[\"\'](.+?)[\"\']\}'sie","\$this->include_parse('\\1')",$str);
        $str = preg_replace("'\{THEME\}'si",'<?=TPL_ALT_DIR?>',$str);
        $str = preg_replace("'\{rating\}'si",'',$str);
        $str = preg_replace("'\{link-category\}'si",'',$str);
        
        $str = preg_replace("'\[edit\]'si",'<? if($sl->auth->member_id[\'admin_ac\']){ ?>',$str);
        $str = preg_replace("'\[\/edit\]'si",'<? } ?>',$str);
        $str = preg_replace("'\[edit-date\](.*?)\[\/edit-date\]'si",'',$str);
        $str = preg_replace("'\[edit-reason\](.*?)\[\/edit-reason\]'si",'',$str);
        $str = preg_replace("'\[fixed\](.*?)\[\/fixed\]'si",'',$str);
        $str = preg_replace("'\[not-fixed\](.*?)\[\/not-fixed\]'si",'',$str);
        $str = preg_replace("'\[day-news\](.*?)\[\/day-news\]'si",'',$str);
        $str = preg_replace("'\[catlist=.*?\](.*?)\[\/catlist\]'si",'',$str);
        $str = preg_replace("'\[not-catlist=.*?\](.*?)\[\/not-catlist\]'si",'',$str);
        $str = preg_replace("'\[poll\](.*?)\[\/poll\]'si",'',$str);
        $str = preg_replace("'\[not-poll\](.*?)\[\/not-poll\]'si",'',$str);
        $str = preg_replace("'\[profile\](.*?)\[\/profile\]'si","\\1",$str);
        $str = preg_replace("'\[complaint\](.*?)\[\/complaint\]'si",'',$str);
        $str = preg_replace("'\[related-news\](.*?)\[\/related-news\]'si",'',$str);
        $str = preg_replace("'\[print-link\](.*?)\[\/print-link\]'si",'',$str);
        $str = preg_replace("'\[tags\](.*?)\[\/tags\]'si",'',$str);
        
        $str = preg_replace("'\[full-link\]'si",'<a href="/static_page/full/<?=$row[\'id\']?>">',$str);
        $str = preg_replace("'\[\/full-link\]'si",'</a>',$str);
        
        $str = preg_replace("'\[comments\](.*?)\[\/comments\]'si","\\1",$str);
        
        $str = preg_replace("'\[not-comments\](.*?)\[\/not-comments\]'si","",$str);
        
        $str = preg_replace("'\{full-link\}'si",'/static_page/full/<?=$row[\'id\']?>',$str);
        
        $str = preg_replace("'\{favorites\}'si",'',$str);
        $str = preg_replace("'\{title\}'si",'<?=$row[\'title\']?>',$str);
        $str = preg_replace("'\{news-id\}'si",'<?=$row[\'id\']?>',$str);
        $str = preg_replace("'\{author\}'si",'',$str);
        $str = preg_replace("'\{short-story\}'si",'<?=$row[\'news\']?>',$str);
        $str = preg_replace("'\{full-story\}'si",'<?=$row[\'news\']?>',$str);
        $str = preg_replace("'\{category-icon\}'si",'',$str);
        $str = preg_replace("'\{favorites\}'si",'',$str);
        $str = preg_replace("'\{edit-date\}'si",'',$str);
        $str = preg_replace("'\{editor\}'si",'',$str);
        $str = preg_replace("'\{edit-reason\}'si",'',$str);
        $str = preg_replace("'\{date=.*?\}'si","",$str);
        $str = preg_replace("'\{approve\}'si",'',$str);
        $str = preg_replace("'\{related-news\}'si",'',$str);
        $str = preg_replace("'\{complaint\}'si",'',$str);
        $str = preg_replace("'\{poll\}'si",'',$str);
        $str = preg_replace("'\{tags\}'si",'',$str);
        $str = preg_replace("'\{pages\}'si",'',$str);
        $str = preg_replace("'\{comments\}'si",'',$str);
        $str = preg_replace("'\{addcomments\}'si",'',$str);
        $str = preg_replace("'\{navigation\}'si",'',$str);
        
        $str = preg_replace("'\{views\}'si",'',$str);
        $str = preg_replace("'\{date\}'si",'<?=date(\'l, j F Y H:i\',strtotime($row[\'date\']))?>',$str);
        $str = preg_replace("'\{comments-num\}'si",'',$str);
        
        $str = preg_replace("'\[com-link\]'si",'',$str);
        $str = preg_replace("'\[\/com-link\]'si",'',$str);
        
        $str = $this->group_parse($str);
        $str = $this->parse_tag($str);
        
        return $str;
    }
    private function parseFile($str,$name){
        switch ($name){ 
        	case 'main':
                
                $header = '
<title><?=$sl->title->get(\'title\')?></title>
<meta name="description" content="<?=$sl->title->get(\'descr\')?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=$sl->scin->js(\'cufon,arial.font,jquery,jquery_ui,jquery.filedrop,sl,scin,scroll\',\'/plugins/js\')?>
<?=$sl->scin->css(\'ui-lightness/jquery-ui-1.8.19.custom,sl_light,tools,animate,form_light,scroll,aero\',\'/plugins/css\')?>
<?=$sl->header?>
                ';
                
                $str = preg_replace("'\{include file=[\"\'](.+?)[\"\']\}'sie","\$this->include_parse('\\1')",$str);
                $str = preg_replace("'\{AJAX\}'si",'',$str);
                $str = preg_replace("'\{headers\}'si",$header,$str);
                $str = preg_replace("'\{THEME\}'si",'<?=TPL_ALT_DIR?>',$str);
                $str = preg_replace("'\{content\}'si",'<?=($moduleInfo[0] ? $sl->content : $sl->static_page->all())?>',$str);
                $str = preg_replace("'\{login\}'si",'<? include TPL_DIR.\'/login.php\' ?>',$str);
                $str = preg_replace("'\{speedbar\}'si",'',$str);
                $str = preg_replace("'\{info\}'si",'',$str);
                $str = preg_replace("'\{tags\}'si",'',$str);
                $str = preg_replace("'\{vote\}'si",'',$str);
                $str = preg_replace("'\{calendar\}'si",'',$str);
                $str = preg_replace("'\{topnews\}'si",'',$str);
                $str = preg_replace("'\{archives\}'si",'',$str);
                $str = preg_replace("'\{referer\}'si",'',$str);
                $str = preg_replace("'\{inform_dle\}'si",'',$str);
                $str = preg_replace("'\{inform_(.*?)\}'si",'',$str);
                $str = preg_replace("'\{banner_(.*?)\}'si",'',$str);
                $str = preg_replace("'\{custom(.*?)\}'si",'',$str);
                $str = preg_replace("'\{changeskin\}'si",'',$str);
                $str = preg_replace("'\[sort\](.*?)\[\/sort\]'si",'',$str);
                $str = preg_replace("'\[aviable=.*?\](.*?)\[\/aviable\]'si","\\1",$str);
                $str = preg_replace("'\[not-aviable=.*?\](.*?)\[\/not-aviable\]'si","",$str);
                
                $str = preg_replace("'\[group=5\](.*?)\[\/group\]'si","<? if(!\$sl->auth->member_id){?>\\1<?}?>",$str);
                $str = preg_replace("'\[group=1\](.*?)\[\/group\]'si","<? if(\$sl->auth->member_id['admin_ac']){?>\\1<?}?>",$str);
                $str = preg_replace("'\[group=[0-9]\](.*?)\[\/group\]'si","\\1",$str);
                
                $str = preg_replace("'\[not-group=5\](.*?)\[\/not-group\]'si","<? if(\$sl->auth->member_id){?>\\1<?}?>",$str);
                $str = preg_replace("'\[not-group=[0-9]\](.*?)\[\/not-group\]'si","\\1",$str);
                
                $str = preg_replace("'\[category=.*?\](.*?)\[\/category\]'si","\\1",$str);
                $str = preg_replace("'\[not-category=.*?\](.*?)\[\/not-category\]'si","\\1",$str);
                
                $str = str_replace('windows-1251','UTF-8',$str);
            
        	break;
        
        	case 'login':
            
                $str = $this->group_parse($str);
        
                $str = preg_replace("'\{THEME\}'si",'<?=TPL_ALT_DIR?>',$str);
            
                $str = preg_replace("'\{\$member_id\[[\'\"]name[\'\"]\]\}'si",'$sl->auth->member_id[\'login\']',$str);
                $str = preg_replace("'\{login\}'si",'<?=$sl->auth->member_id[\'login\'] ?>',$str);
                $str = preg_replace("'\{logout-link\}'si",'/auth/logout',$str);
                $str = preg_replace("'\{\$link_logout\}'si",'/auth/logout',$str);
                $str = preg_replace("'\{admin-link\}'si",'admin.php',$str);
                $str = preg_replace("'\{\$adminlink\}'si",'admin.php',$str);
                $str = preg_replace("'\{new-pm\}'si",'0',$str);
                $str = preg_replace("'\{all-pm\}'si",'0',$str);
                $str = preg_replace("'\{profile-link\}'si",'',$str);
                $str = preg_replace("'\{addnews-link\}'si",'',$str);
                $str = preg_replace("'\{pm-link\}'si",'',$str);
                $str = preg_replace("'\{favorites-link\}'si",'',$str);
                $str = preg_replace("'\{stats-link\}'si",'',$str);
                $str = preg_replace("'\{favorite-count\}'si",'0',$str);
                $str = preg_replace("'\{foto\}'si",'<?=TPL_ALT_DIR.\'/images/noavatar.png\'?>',$str);
                $str = preg_replace("'\{\$foto\}'si",'<?=TPL_ALT_DIR.\'/images/noavatar.png\'?>',$str);
                
                $str = preg_replace("'\[admin-link\]'si",'<? if($sl->auth->member_id[\'admin_ac\']){ ?>',$str);
                $str = preg_replace("'\[\/admin-link\]'si",'<? } ?>',$str);
                
        	break;
        
        	case 'shortstory':
                $str = $this->parse_news($str);
        	break;
            
            case 'fullstory':
                $str = $this->parse_news($str);
        	break;
        
        	default :
        }
        
        return $str;
    }
    function recove($i){
        if(!$this->ajaxLoad) return;
        if($this->sl->fn->check_ac('admin')) return;
        
        $i = intval($i);
        
        $tpl = $this->show_list();
        $this->tpl = $tpl[$i][0];
        
        $lang = $this->sl->fn->lang([
            'Ошибка обработки шаблона, шаблон не найден',
            'Шаблон не распознан или не является DLE шаблоном'
        ]);
        
        if(!$this->tpl) $this->sl->fn->info($lang[0]);
        
        $tpah = SL_DIR.DIR_SEP.'tpl'.DIR_SEP.$this->tpl.DIR_SEP;
        
        if(!file_exists($tpah.'main.tpl')) $this->sl->fn->info($lang[1]);
        
        $pr   = ['main.tpl','login.tpl','login.php','shortstory.tpl','fullstory.tpl'];
        $prCo = [
            'main'=>'index.php',
            'login'=>'login.php',
            'shortstory'=>'static_page.php',
            'fullstory'=>'static_page_section.php'
        ];
        $scan = $this->sl->fn->scan($tpah);
        
        foreach($scan['file'] as $n){
            $ex = substr($n,-3);
            
            if(in_array($n,$pr)){
                $file = file_get_contents($tpah.$n);
                
                $name = substr($n,0,-4);
                
                $file = $this->parseFile($file,$name);
                
                unlink($tpah.$n);
                
                $file = "<?if(!defined('SL_DIR')) die()?>\n".$file;
                
                $file = iconv("cp1251","UTF-8",$file);
                
                file_put_contents($tpah.$prCo[$name],$file);
                
            }
            elseif($ex == 'tpl') unlink($tpah.$n);
        }
    }
    function show($name = ''){
        $lang = $this->sl->fn->lang([
            'Выберите шаблон',
            'Поздравляем! шаблон успешно был обработан'
        ]);
        
        $scr = "
            <script>
            $.sl('big_select','{$lang[0]}',{
                load:'/ajax/{$this->modInfo[0]}/show_list'
            },function(i){
                $.sl('install','/ajax/{$this->modInfo[0]}/recove/'+i,function(){
                    $('#{$this->modInfo[0]}_result').text('{$lang[1]}');
                });
            })
            </script>
            <div class=\"win_h_size_shell t_p_r\"><div class=\"t_p_a t_left t_top_50 t_width t_center\" id=\"{$this->modInfo[0]}_result\"></div></div>
        ";
        
        return $scr;
    }
}
?>