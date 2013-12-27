<?php
/**
// SL 5
//
// Version 5
//
// Author Korner
// Sl SYSTEM
// 06 June 2012
//
// Visit http://sl-cms.com for more information
//
// TERMS OF USE
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2012 Sl SYSTEM, LLC. 
*/

define('SL_DIR',dirname(__FILE__));
define('DIR_SEP',DIRECTORY_SEPARATOR);
define('SL_ENGINE',SL_DIR.DIR_SEP.'engine');
define('SL_DATA',SL_DIR.DIR_SEP.'data');
define('SL_CACHE',SL_DIR.DIR_SEP.'cache');
define('SL_UPLOAD',SL_DIR.DIR_SEP.'upload');
define('SL_MODULES',SL_DIR.DIR_SEP.'modules');
define('SL_PLUGINS',SL_DIR.DIR_SEP.'plugins'.DIR_SEP.'php');
define('ADMINFILE',true);

include_once SL_ENGINE.DIR_SEP.'multi_dn.php';

header('Content-type: text/html; charset=utf-8');

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( "short_open_tag", 1 );
session_start();

include_once SL_ENGINE.'/class.php';

/**
 * Инициализация модулей
 */
$init_mod = $sl->fn->conf('get',SL_DATA.DIR_SEP.'init_modules');

foreach((is_array($init_mod) ? $init_mod : []) as $key=>$arr){
    if($key != '' && $arr['method'] != '' && $arr['status'] == 1){
        if($arr['type'] == 1 || $arr['type'] == 4 || $arr['type'] == 5) $sl->$key->$arr['method']();
    } 
}
?>