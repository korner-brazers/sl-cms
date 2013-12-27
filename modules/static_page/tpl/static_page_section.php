<?if(!defined('SL_DIR')) die()?>

<div class="news_big">
    <h3><?=$row['title']?></h3>
    <?=$sl->images->show_img($row['id'],'','size',379,205)?>
    <p>
    <?=$row['news']?>
    </p>
    <div class="t_clear"></div>
    <div class="info">'Последние обновление'<?=date('l, j F Y H:i',strtotime($row['date']))?></div>
    
</div>