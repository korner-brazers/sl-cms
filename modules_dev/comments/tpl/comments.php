<div class="com_block">
    <p><?=$sl->xcode->filter($row['text'])?></p>
    <span class="commentAuthorName"><?=$row['user_name']?></span>
    <span class="commentDate"><?=$sl->fn->rus_date('l, j F Y i:s',strtotime($row['date']))?></span>
    <?=$del_btn?>
</div>