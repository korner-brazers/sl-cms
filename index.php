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
define('INDEXFILE',true);

include_once SL_ENGINE.DIR_SEP.'multi_dn.php';

header('Content-type: text/html; charset=utf-8');

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( "short_open_tag", 1 );

include_once SL_ENGINE.DIR_SEP.'php_info.php';

session_start();

include_once SL_ENGINE.DIR_SEP.'class.php';
include_once SL_ENGINE.DIR_SEP.'router.php';

if($sl->tpl()) echo $sl->tpl->display('index');
else echo $sl->stpl->index_error('Шаблон ('.TPL_NAME.'-index.php) не найден');
?>