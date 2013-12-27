<!DOCTYPE HTML>
<head>
<title><?=$sl->settings->get('tpl_title')?></title>
<meta name="description" content="<?=$sl->settings->get('tpl_desc')?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?=$sl->scin->js(['plugins/js/cufon','plugins/js/corbel.font','plugins/js/jquery'])?>
<?=$sl->scin->js(['js/ready','js/bg_position'])?>
<?=$sl->scin->css('css/style,css/tools')?>
<?
$gettime = $sl->settings->get('tpl_time');

if (($time = strtotime($gettime)) === -1 || $gettime == '') $time = time()+60*60*24*30;

?>
<script>
var dir = '/';
var date = '<?=date('F',$time)?>, <?=date('d',$time)?> <?=date('Y',$time)?>';
</script>
</head>

<body>

<div class="bg position_f top left width height"></div>
<div class="bg2 position_f top left width height"></div>

<div class="dark position_f top left width height hide"></div>
<div class="loading position_f top left width height"></div>

<div id="wrap" class="overhide position_a top left width hide">
  <div class="position_r">
      <div class="header">
        <div class="body_width">
          
          <ul class="nav">
          <li><a href="/index.php"><h2>Главная</h2></a></li>
          
          <li><a href="#"><h2>Форум</h2></a></li>
          
          
          </ul>
        </div>
      </div>
      
      <div id="content">
        <div class="body_width position_r">
            <div id="conteiner" class="body_width">
                
                
                <div class="left w_50">
                    <div class="padding_30" style="padding-left: 0;">
                        <h1>Cайт на реконструкции</h1>
                        <p>Уважаемые посетители сайта, партнеры и клиенты! <br />Сайт находится на реконструкции.<br />Приносим извинения за причиненные временные неудобства! <br /> Зайдите позже когда закончится таймер!</p>
                    </div>
                </div>
                
                <div class="left w_50" style="padding-top: 50px;">
                    <h1 class="big_4">До открытия</h1>
                    <h1 class="big_2">Осталось</h1>
                </div>
                
                <div class="clear"></div>
                
                <h2 class="left time" style="margin-left: 260px;margin-right: 90px;">Дней</h2>
                <h2 class="left time" style="margin-right: 82px;">Часов</h2>
                <h2 class="left time" style="margin-right: 76px;">Минут</h2>
                <h2 class="left time" style="margin-right: 10px;">Секунд</h2>
                <div class="clear"></div>
                <h1 id="dateReady" class="left" style="font-size: 90px; margin-top: -35px"></h1>
                <div class="clear"></div>
                
                
                <div style="visibility: hidden;height: 1px; z-index: 20;" class="position_r">
                
                <object type="application/x-shockwave-flash" data="http://www.filestube.com/audio/player.swf" id="audioplayer1" height="1" width="1">
                    <param name="movie" value="http://www.filestube.com/audio/player.swf">
                    <param name="FlashVars" value="playerID=1&autostart=yes&text=0x000000&loader=0xBFE4FF&slider=0x007CD9&soundFile=/bg.mp3&gig_lt=1254156687626&gig_pt=1254156691541&gig_g=1">
                    <param name="quality" value="low">
                    <param name="menu" value="false">
                    <param name="quality" value="high">
                    <param name="menu" value="false">
                    <param name="wmode" value="transparent">
                </object>
                </div>

                
                <img src="/images/music.gif" style="cursor: pointer;top: -55px;right: 50px" onclick="$('#audioplayer1').remove(); $(this).remove()" class="position_a right" />
                <div class="clear"></div>
                <div class="titles left position_r" style="height: 40px;width: 100%;">
                    <h1 class="position_a top left">Авторы: Korner and Neoks</h1>
                    <h1 class="position_a top left">Дизайн студия: QWARP</h1>
                    <h1 class="position_a top left">Саундтрек: - Watchmen</h1>
                    <h1 class="position_a top left">Скрипт: SL-5</h1>
                    <h1 class="position_a top left">Copyright © 2012 SL SYSTEM</h1>
                </div>
                <div class="clear"></div>
            </div>
        </div>
      </div>
      
      
      <div class="footer">
        <div class="body_width">
          
          <div class="padding_20">
          <div style="text-align: center;">
            <h4 style="margin: 0 auto;">Copyright © 2012 SL SYSTEM. All Rights Reserved</h4>
            </div>
          </div>
    
        </div>
      </div>
      
  </div>
</div>
</body>
</html>