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
 
$this->sl->scin->css('style','/modules/desktop');
$this->sl->scin->css(['tools','animate','sl','scroll','form','aero','ui-lightness/jquery-ui-1.8.19.custom'],'/plugins/css');
$this->sl->scin->js(['jquery','sl','scin','scroll','cufon','arial.font','jquery_ui'],'/plugins/js');
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
    'Вход',
    'В админ панель',
    'Войти'
]);
?>
<div id="win_bg" class="t_p_a t_width t_height">
<img id="bg_img" src="/upload/admin_bg/default.jpg" style="width: 0px; height: 0px; display: none;" />
<img id="bg_blur" src="/upload/admin_bg/blur.jpg" style="width: 0px; height: 0px; display: none;" />
<div id="bg" class="t_p_a t_width t_height"></div>
<div id="wrap" class="t_p_a t_width t_height">
<form method="post" action="javascript:this.preventDefault()">
    <div id="install" style="display: none;">
        <div class="t_p_a t_left_50" style="top: 10%; margin-left: -80px;">
            <h1 class="smooth t_size_3 t_left"><?=$lang[0]?></h1>
            <h1 class="smooth t_size_5 t_left" style="margin-top: 2px; margin-left: 10px;"><?=$lang[1]?></h1>
        </div>
        
        <div class="t_p_a t_left t_width" style="top: 35%; height: 95px; margin-top: -10px; padding-top: 10px;">
            <div class="user_b t_p_r">
                <ul class="all_users t_p_a t_left_50">
                    <li class="active">
                        <div class="t_left aero_radius aero_shadow ava">
                            <img src="/modules/<?=$this->modInfo[0]?>/images/ava.png" />
                        </div>
                        <div class="t_left" style="margin-top: 3px;">
                            <h1 class="t_left user_name smooth">Root</h1>
                            <div class="t_clear"></div>
                            <h4 class="t_left user_ac smooth">SuperAdmin</h4>
                        </div>
                        <input type="hidden" name="login" value="root" />
                    </li>
                    
                    <?
                    
                    if($this->sl->db->connect(false)){
                        $this->sl->db->alterTableAdd('users',[
                            'login'=>['VARCHAR',100],
                            'email'=>['VARCHAR',100],
                            'password'=>['VARCHAR',32],
                            'admin_ac'=>['SMALLINT',1,0],
                            'date'=>['DATETIME NOT NULL',false,'0000-00-00 00:00:00'],
                        ]);
                        
                        $this->sl->db->get_while(function($row){
                            
                            ?>
                            <li>
                                <div class="t_left aero_radius aero_shadow ava">
                                <?
                                    echo '<img src="/modules/'.$this->modInfo[0].'/images/ava.png" />';
                                ?>
                                </div>
                                <div class="t_left" style="margin-top: 3px;">
                                    <h1 class="t_left user_name smooth" style="font-size: 16px"><?=$this->sl->fn->substr($row['login'],0,5,'_')?></h1>
                                    <div class="t_clear"></div>
                                    <h4 class="t_left user_ac smooth">Admin</h4>
                                </div>
                                <input type="hidden" name="login" value="<?=$row['login']?>" disabled="" />
                            </li>
                            <?
                        },$this->sl->db->select('users','admin_ac=1 AND login!="root"'));
                        
                        ?>
                        <script>
                            users = 1 + <?=$this->sl->db->count('users','admin_ac=1 AND login!="root"')?>;
                        </script>
                        <?
                    }
                    
                    ?>
                    
                </ul>
            </div>
        </div>
        
        <div class="point_right aero_bg t_p_a t_right"></div>
        <div class="point_right t_p_a t_right p_r"></div>
        
        <div class="point_left aero_bg t_p_a tleft"></div>
        <div class="point_left t_p_a t_left p_l"></div>
        
        <div class="t_p_a t_left_50 install" style="top: 35%;">
            <div style="height: 65px;"></div>
            <div class="t_clear"></div>
            <div class="sepi"></div>
            
            <div class="password t_left aero_radius aero_shadow" style="margin-right: 10px;">
                <input type="password" name="password" />
            </div>
            <div class="t_clear"></div>
            <div class="btn_auth t_left" onclick="$(this).sl('load','/ajax/<?=$this->modInfo[0]?>/login',{back:false},function(){ window.location = '<?=$_SERVER["REQUEST_URI"]?>'; })"><span style="color: #272626;"><?=$lang[2]?></span></div>
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