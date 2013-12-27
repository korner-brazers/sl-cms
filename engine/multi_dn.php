<?
$onoff = false; //включить/выключить многозадачность, используйте true-включить или false-выключить

if($onoff) define('MULTI_DN',preg_replace("'[^a-z0-9]'si",'_',str_replace('www.','',$_SERVER["HTTP_HOST"])));
else       define('MULTI_DN','');
?>