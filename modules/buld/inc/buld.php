<?
if(!defined('SL_DIR')) die();
?>

<div id="buld" class="t_p_r">
<table class="buld_table win_h_size" minus="64" cellspacing="0" cellpadding="0">
    <tr>
        <td class="wi">
            <div class="tools_sep"></div>
            <ul class="tools left_tools">
                <li class="active"><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/default.png" /></li>
                <li><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/remove.png" /></li>
                <li><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/brush.png" /></li>
                <li><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/move.png" /></li>
                <li onclick="$(this).buld('btnZoom')"><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/zoom.png" /></li>
            </ul>
        </td>
        <td class="vis" style='-moz-user-select: none;-webkit-user-select: none;' onselectstart='return false;'>
            
            <div class="visual_bg_conteiner win_h_size_shell t_over t_p_r" minus="64">
                
                <div class="conteiner_position_layer active t_p_a t_top t_left">
                    <form method="post" id="form">
                        <div class="conteiner_obj t_p_r">
                            <!--canvas-->
                            <canvas height="300" width="300" class="canvas" id="buld_canvas_"></canvas>
                        </div>
                    </form>
                </div>
                
            </div>
            
        </td>
        <td class="wi">
            <div class="tools_sep"></div>
            <ul class="tools right_tools">
                <li buldTab="0"><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/lib.png" /></li>
                <li class="sepl"><span buldTab="1"><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/obj.png" /></span></li>
                <li class="sepl"><span buldTab="2"><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/prj.png" /></span></li>
                <li class="sepl"><span buldTab="3"><img src="/modules/<?=$this->modInfo[0]?>/media/img/to/nav.png" /></span></li>
            </ul>
            <div id="panels">
                <div class="win_h_size_shell co scrollbarInit" minus="64">
                    <div class="header">
                        <h3><?=$lang[4]?></h3>
                        <div class="btns">
                            <?=$this->sl->scin->btn('&#174;',['attr'=>['onclick'=>"$.buld('iniLib')"]])?>
                            <?=$this->sl->scin->btn('&#8801;',['attr'=>['onclick'=>"$.buld('editLib')"]])?>
                        </div>
                    </div>
                    <div class="sep"></div>
                    <ul class="libraly_list"></ul>
                </div>
                <div class="win_h_size_shell co scrollbarInit" minus="64">
                    <div class="objPropertiesConteiner"></div>
                </div>
                <div class="win_h_size_shell co scrollbarInit" minus="64">
                    <div class="header">
                        <h3><?=$lang[5]?></h3>
                        <div class="btns">
                            <?=$this->sl->scin->btn($lang[6],['attr'=>['onclick'=>"$(this).sl('scroll_menu',{load:'/ajax/buld/jMenuListPrj'},function(i){ $.buld('openPrj',i) })"]])?>
                            <?=$this->sl->scin->btn('&#8801;',['attr'=>['onclick'=>"$.buld('editPrj')"]])?>
                        </div>
                    </div>
                    <div class="sep"></div>
                    <ul class="list_style prjListLi"></ul>
                </div>
                <div class="win_h_size_shell co scrollbarInit" minus="64" id="objListNavCo">
                    <div class="header">
                        <h3><?=$lang[7]?></h3>
                        <div class="btns">
                            <?=$this->sl->scin->btn($lang[8],['attr'=>['onclick'=>"$(this).sl('_promt',{input:['name'],bg:false,autoclose:false,btn:{'$lang[9]':function(w,i){ $.buld('objListNav',i[0].value) }}})"]])?>
                        </div>
                    </div>
                    <div class="sep"></div>
                    <ul class="list_style objListNav"></ul>
                </div>
            </div>
        </td>
    </tr>
</table>
<div class="buld_bottom t_clearfix">
    <div class="t_left l" id="infoRm"></div>
    <div class="t_right c">Copyright 2012 SL SYSTEM. All Rights Reserved. Design studio <a href="http://qwarp.sl-cms.com" target="_blank">Qwarp</a></div>
</div>
<div class="m_logo"></div>
</div>