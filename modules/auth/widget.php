<?if(!defined('SL_DIR')) die()?>
<?
$lang = $this->sl->fn->lang([
    'Войти',
    'Регистрация',
    'Выход',
    'Плагин CSS и JS для модуля (auth) не подключены',
    'Заполните все поля'
]);

$langScript = $this->sl->fn->lang([
    'Заполните все поля',
    'Регистрация',
]);
?>
<div class="authWidget t_clearfix">
    <div class="loadAuth" style="display: none;">
        <?if(!$this->member_id){?>
        <form method="post" id="authForm">
            <div class="input login"><input type="text" name="login" /></div>
            <div class="input password"><input type="password" name="password" /></div>
            <div class="btn_conteiner">
                <div class="btnLogin bt"><span><?=$lang[0]?></span></div>
                <div class="btnReg bt"><span><?=$this->sl->fn->substr($lang[1],0,10)?></span></div>
                <div class="or"></div>
            </div>
        </form>
        <?}else{?>
        <div class="ava">
            <div class="bgAva">
                <img src="/modules/<?=$this->modInfo[0]?>/images/ava.png" />
            </div>
        </div>
        <div class="info">
            <b class="userName"><?=$this->member_id['login']?></b>
            <div class="btnOut"><?=$lang[2]?></div>
        </div>
        <?}?>
    </div>
    <div class="preloadAuth" style="height: 40px; background: url(/plugins/images/sl_loading/quiet_light.gif) no-repeat 50% 50%;"></div>
    <script>
        if(window.authPluginInclude == undefined){
            setTimeout(function(){
                $('.authWidget .preloadAuth').css({background: 'none',height: 'auto'}).text('<?=$lang[3]?>')
            },1000);
        }else <?=$this->modInfo[0]?>InitPlugin();
        
        var <?=$this->modInfo[0]?>Lang = <?=json_encode($langScript)?>;
    </script>
</div>
