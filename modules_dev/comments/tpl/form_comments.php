<style>
.comments{
    padding-top: 20px;
    margin-bottom: 20px;
}
.comments h3{
    font-size: 20px;
    color: #646566;
}
.comments .com_block{
    background: #f5f5f5;
    padding: 10px;
    margin-top: 15px;
    position: relative;
}
.comments .com_block:first-child{
    margin-top: 0;
}
.comments .com_block p{
    margin: 0 0 24px;
    padding: 3px 0;
}
.comments .com_block span.commentAuthorName{
    border-top: 1px solid #e7e7e7;
    display: block;
    font-weight: bold;
    color: #000;
    padding: 5px 0;
}
.comments .com_block span.commentDate{
    font-size: 10px;
    color: #a2a2a2;
}
.comments div.name .name_in{
    padding: 10px 0 0 10px;
}
.comments div.name{
    margin: 0 0 2px 0;
}
.comments div.text{
    height: 200px;
}
.comments div.text .sl_textarea,
.comments .sl_btn{
    margin: 0;
}
.comments .sl_btn:last-child{
    margin-top: 10px;
}
.comments ._nav{
    padding-top: 10px;
}
.comments ._nav .sl_btn{
    margin: 3px 5px;
}
.comments .com_block .del_com{
    position: absolute;
    bottom: 10px;
    right: 10px;
    opacity: 0;
    font-style: italic;
    cursor: pointer;
    color: #83beda;
    -moz-transition: all 0.2s ease-out; 
    -webkit-transition: all 0.2s ease-out; 
    -o-transition: all 0.2s ease-out;
}
.comments .com_block:hover .del_com{
    opacity: 1;
}
</style>
<h3>Коменнтарии</h3>
<?=$comments?>
<?=$nav?>
<div class="t_p_10"></div>
<?if($any){?>
    <?=($name_user ? '<div class="name t_clearfix"><div class="t_left">'.$name_user.'</div><div class="t_left name_in">Ваше имя*</div></div>' : '')?>
    
    <div class="text"><?=$textarea?></div>
    <?=$btn_add?>
    
    <?}else{?>
    <h3>Вы не авторизованы</h3>
    <p>Вы не авторизованы, пожалуйста авторизуйтесь чтоб оставить свой комментарий</p>
<?}?>
