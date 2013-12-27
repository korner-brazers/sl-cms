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
$this->sl->scin->js(['jquery','sl','scin','scroll','cufon','arial.font','jquery_ui'],'/plugins/js');
$this->sl->scin->css('style','/modules/desktop');
?>
<body class="t_over">
<?=$this->sl->scin->cache_js(__DIR__)?>
<?=$this->sl->scin->cache_css(__DIR__)?>
<style>
.smooth{
    color: #fff !important;
}
</style>
<?
$lang = $this->sl->fn->lang([
    'Новая',
    'учетная запись',
    'Установить'
]);
?>
<div id="win_bg" class="t_p_a t_width t_height">
<img id="bg_img" src="/upload/admin_bg/default.jpg" style="width: 0px; height: 0px; display: none;" />
<img id="bg_blur" src="/upload/admin_bg/blur.jpg" style="width: 0px; height: 0px; display: none;" />
<div id="bg" class="t_p_a t_width t_height"></div>
<div id="wrap" class="t_p_a t_width t_height">
<form method="post" action="javascript:this.preventDefault()">
    <div id="install" style="display: none;">
        <div class="t_p_a t_left_50" style="top: 10%; margin-left: -100px;">
            <h1 class="smooth t_size_3 t_left"><?=$lang[0]?></h1>
            <h1 class="smooth t_size_5 t_left" style="margin-top: 2px; margin-left: 10px;"><?=$lang[1]?></h1>
        </div>
        
        <div class="t_p_a t_left_50 install" style="top: 35%;">
            <div class="t_left aero_radius aero_shadow ava" style="margin-right: 10px; margin-left: 28px;">
                <img src="/modules/<?=$this->modInfo[0]?>/images/ava.png" />
            </div>
            <div class="t_left" style="margin-top: 3px;">
                <h1 class="t_left user_name smooth">Root</h1>
                <div class="t_clear"></div>
                <h4 class="t_left user_ac smooth">SuperAdmin</h4>
            </div>
            <div class="t_clear"></div>
            <div class="sepi"></div>
            
            <div class="password t_left aero_radius aero_shadow" style="margin-right: 10px;">
                <input type="password" name="password" />
            </div>
            <div class="t_clear"></div>
            <div class="btn_auth t_left" style="margin-left: 22px" onclick="$(this).sl('load','/ajax/<?=$this->modInfo[0]?>/new_ac',{back:false},function(){ window.location = '<?=$_SERVER["REQUEST_URI"]?>'; })"><span style="color: #272626;"><?=$lang[2]?></span></div>
        </div>
    </div>
</div>
</form>
</div>
<script>
    DSS.setSize();
</script>
<?
exit;
?>