<?if(!defined('SL_DIR')) die()?>

<div class="news_block">
    <?=$sl->images->show_img($row['id'],'','size',579,235)?>
    <h3><a href="/<?=($moduleInfo[0] ? $moduleInfo[0] : "static_page")?>/full/<?=$row['id']?>" class="t_animate"><?=$row['title']?></a></h3>
    <div class="date"><?=date('l, j F Y H:i',strtotime($row['date']))?></div>
    <p><?=$row['news']?></p>
    <a href="/<?=($moduleInfo[0] ? $moduleInfo[0] : "static_page")?>/full/<?=$row['id']?>" class="read_more t_animate">Подробнее</a>
</div>