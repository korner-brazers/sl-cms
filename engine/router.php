<?
/**
 * @sl class
 * Роутер URL адресов
 * @author korner
 * @copyright SL-SYSTEM 2012
 */

if(!defined('SL_DIR')) die();
 
$uri = $_SERVER["REQUEST_URI"];

if(preg_match("'^/index.php'i",$uri)) $uri = '';

$realPathS = str_replace('index.php','',$_SERVER['SCRIPT_NAME']);

$uri = substr($uri,strlen($realPathS),strlen($uri));

$uri = preg_replace("'([/]+)'si",'/',$uri);

if($uri[0] == '/') $uri = substr($uri,1,strlen($uri));

$xp = explode('/',$uri);

$ajaxLoad = $xp[0] == 'ajax' ? true : false;

if($ajaxLoad) array_shift($xp);

$uri_r = implode('/',$xp);

/**
 * Параметры модуля
 */
 
$modul  = preg_replace("'[^a-z0-9_]'si",'',$xp[0]);
$action = preg_replace("'[^a-z0-9_]'si",'',$xp[1]);
$params = array_slice($xp,2);

foreach($params as $pk=>$pv) $params[$pk] = urldecode($pv);

if($modul && !$action) $action = 'show';

$moduleInfo = [$modul,$action,$params,$ajaxLoad,true,false];

/**
 * Параметры шаблона
 */

$tpl = $sl->settings->get('tpl_name');
 
define('TPL_NAME',$tpl == '' ? 'default' : $tpl);
define('TPL_DIR',SL_DIR.DIR_SEP.'tpl'.DIR_SEP.TPL_NAME);
define('TPL_ALT_DIR','/tpl/'.TPL_NAME);

/**
 * Редирект на файл если есть
 */
 
if($uri !== '' && file_exists(TPL_DIR.DIR_SEP.$uri) && !strstr(end($xp),'php') && is_file(TPL_DIR.DIR_SEP.$uri)){
    header('Location: ' . TPL_ALT_DIR.'/'.$uri);  
    die();
}
/**
 * Чтоб не грузить полностью сайт если файл с расширением не найден
 */
 
if($sl->fn->extension(['png','jpg','jpeg','gif','css','js'],$uri)) die();

/**
 * Инициализация модулей
 */
$init_mod = $sl->fn->conf('get',SL_DATA.DIR_SEP.'init_modules');

foreach((is_array($init_mod) ? $init_mod : []) as $key=>$arr){
    $findEx = false;
    
    if($key != '' && $arr['method'] != '' && $arr['status'] == 1){
        $excluded = explode("\n",trim($arr['excluded']));
        
        foreach($excluded as $goex){
            $goex = trim($goex);
            if($goex !== ''){
                if(@substr($uri_r,0,strlen($goex)) == $goex){
                    $findEx = true;
                    break;
                }
            }
        }
        if(!$findEx){
            if(($arr['type'] == 2 || $arr['type'] == 4) || (($arr['type'] == 3 || $arr['type'] == 5) && !$ajaxLoad)){
                
                if($arr['is_module'] !== ''){
                    $is_m_ex = explode("\n",trim($arr['is_module']));
                    
                    foreach($is_m_ex as $ifex){
                        //if(@substr($uri_r,0,strlen($ifex)) == $ifex){
                        if($sl->fn->ifUri($ifex)){
                            $sl->$key->$arr['method']();
                            break;
                        }
                    }
                }
                else $sl->$key->$arr['method']();
            }
        }
    } 
}
/**
 * Загрузка модуля
 */
if($modul !== '' and $action !== '' and !$sl->stopModule){
    
    if($sl->fn->infomod($modul)['status'] == 'on' || $modul == 'fn'){
        if($sl->$modul()){
            if(method_exists($sl->$modul, $action)){
                if(!$ajaxLoad) $sl->$modul->modInfo[5] = true;
                
                $sl->content = call_user_func_array([$sl->$modul, $action], $params);
                
                if(!$sl->stopModuleThis){
                    
                    /**
                     * ILINK
                     */
                     
                    $ilink = $sl->fn->conf('get',SL_DATA.DIR_SEP.'ilink');
                    
                    foreach($ilink as $imod){
                        if($imod['status']){
                           
                            $mPl = $sl->fn->ifUri($imod['get']);
                            
                            if($mPl){
                                
                                foreach($mPl as $i=>$v) $imod['param'] = str_replace('{'.$i.'}',$v,$imod['param']);
                                
                                if($imod['mod'] && $imod['action']) $sl->content .= $sl->$imod['mod']->$imod['action'](
                                    array_map('trim',explode("\n",trim($imod['param']))),
                                    array_map('trim',explode("\n",trim($imod['more_param'])))
                                );
                            }
                        }
                    }
                }
            } 
        }
    }
    else{
        if($ajaxLoad) $sl->fn->info('Модуль ('.$modul.') отключен');
        else $sl->content = $sl->stpl->module_off($modul);
    }
}

if($sl->stopModule){
    if($ajaxLoad) $sl->fn->info('Модуль ('.$modul.') был остановлен по требованию инсталляционного модуля');
    else $sl->content = $sl->stpl->stop_module('Модуль ('.$modul.') был остановлен по требованию инсталляционного модуля');
}

if($sl->stopModuleThis){
    if($ajaxLoad) $sl->fn->info('Модуль ('.$modul.') '.$sl->stopModuleThis);
    else $sl->content = $sl->stpl->stop_module('Модуль ('.$modul.') '.$sl->stopModuleThis);
}

if($ajaxLoad){
    echo is_array($sl->content) ? json_encode($sl->content) : $sl->content; die();
}
?>