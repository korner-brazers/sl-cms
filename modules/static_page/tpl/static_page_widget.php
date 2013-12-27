<?if(!defined('SL_DIR')) die()?>

<li<?=($moduleInfo[2][0] == $row['id'] ? ' class="active"' : '')?> onclick="window.location = '/<?=$moduleInfo[0]?>/full/<?=$row['id']?>'">
    <?=$sl->images->show_img($row['id'],'','size',100,80)?>
    <a href="/<?=$moduleInfo[0]?>/full/<?=$row['id']?>"><?=$row['title']?></a>
    <p><?=$sl->fn->substr($row['descr'],0,140)?></p>
    <div class="t_clear"></div>
</li>