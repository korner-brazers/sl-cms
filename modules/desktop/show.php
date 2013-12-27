<?
if(!defined('SL_DIR')) die();
?>
<!DOCTYPE HTML>
<head>
<title>SL Admin Panel</title>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
$this->sl->scin->print_style = true;
 
$this->sl->scin->css('style','/modules/'.$this->modInfo[0]);
$this->sl->scin->css(['tools','animate','sl','scroll','form','aero','ui-lightness/jquery-ui-1.8.19.custom'],'/plugins/css');
$this->sl->scin->js(['jquery','sl','scin','scroll','cufon','arial.font','jquery_ui','jquery.filedrop','hammer','jquery.hammer'],'/plugins/js');
$this->sl->scin->js('desktop','/modules/'.$this->modInfo[0]);

if(preg_match("'android|ipad'si",$_SERVER['HTTP_USER_AGENT'])){
?>
<style>
    div.icon{
        background: 
        url(/plugins/images/aero/r_t_l.png) no-repeat 0 0,
        url(/plugins/images/aero/r_t_r.png) no-repeat 100% 0,
        url(/plugins/images/aero/r_b_r.png) no-repeat 100% 100%,
        url(/plugins/images/aero/r_b_l.png) no-repeat 0 100%,
        
        url(/plugins/images/aero/border.png) repeat-x 0 0,
        url(/plugins/images/aero/border.png) repeat-y 100% 0,
        url(/plugins/images/aero/border.png) repeat-x 0 100%,
        url(/plugins/images/aero/border.png) repeat-y 0 0,
        url(/plugins/images/aero/bg.png) !important;
        border-radius: 3px;
        border: 0 !important;
    }
</style>
<?
}
?>
<?=$this->sl->header?>
<script>
var pathIco = '/modules/<?=$this->modInfo[0]?>/lib/ico_10.png';
var dslogin = '<?=$_SESSION['user_login']?>';
</script>
</head>

<body class="t_over">
<div id="win_bg" class="t_p_a t_width t_height">
    <img id="bg_img" src="/upload/admin_bg/default.jpg" style="width: 0px; height: 0px; display: none;" />
    <img id="bg_blur" src="/upload/admin_bg/blur.jpg" style="width: 0px; height: 0px; display: none;" />
    <div id="bg" class="t_p_a t_width t_height tabbg"></div>
    <div id="wrap" class="t_p_a t_width t_height"></div>
    <a href="http://sl-cms.com" target="_blank" class="dp_logos"></a>
</div>
<script>
    DSS.setSize();
    
    (function ($) {
        
        $('body').on('touchmove', function(event){
            event.preventDefault();
            event.stopPropagation();
        });
        
    }(jQuery));
    
</script>
</body>
</html>
