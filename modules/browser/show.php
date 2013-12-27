<?
if(!defined('SL_DIR')) die();
?>
<!DOCTYPE HTML>
<head>
<title>Browser is not supported</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
$this->sl->scin->print_style = true;
$this->sl->scin->css(['tools','animate'],'/plugins/css');
$this->sl->scin->js(['jquery'],'/plugins/js');
?>
<script>
$(document).ready(function() {
    $('#bg_img').load(function(){
        setTimeout(function(){
            $('#bg').addClass('bgload');
            $('#error').animate({
                marginTop:'-119px',
                marginLeft:'-297px',
                width: 594,
                height: 238,
                opacity: 1
            },300)<?=($ie ? '.fadeIn()' : '')?>;
        },1000)
    })
});
</script>
<style>
body{
    background: #000;
}
#bg{
    background: url(/plugins/images/sl_loading/content.gif) no-repeat 50% 50% fixed;
}
.bgload{
    background: url(/plugins/images/php_info/bg.png) no-repeat 50% 50% !important;
    opacity: 1;
    -webkit-animation: fadeIn 1s;
    -moz-animation: fadeIn 1s;
    -ms-animation: fadeIn 1s;
    -o-animation: fadeIn 1s;
}
#error{
    width: 100px;
    height: 20px;
    margin: -10px 0 0 -50px;
    opacity: 0;
    <?=($ie ? 'display: none;' : '')?>
}
</style>
</head>

<body class="t_body">

<div id="win_bg" class="t_p_a t_width t_height">
<img id="bg_img" src="/plugins/images/php_info/bg.png" style="width: 0px; height: 0px; display: none;" />
<div id="bg" class="t_p_a t_width t_height<?=($opera ? ' bgload' : '')?>"></div>

<img src="/modules/<?=$this->modInfo[0]?>/error.png" id="error" class="t_p_a t_left_50 t_top_50" />

</div>
</body>
</html>
<?
die();
?>