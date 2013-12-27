<?if(!defined('SL_DIR')) die();?>
<div class="com_block">
    <p><?=$sl->fn->substr($row['text'],0,120)?></p>
    <span class="commentAuthorName"><?=$row['user_name']?></span>
    <span class="commentDate"><?=$sl->fn->rus_date('l, j F Y h:i:s',strtotime($row['date']))?></span>
</div>