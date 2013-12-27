<?
if(!defined('SL_DIR')) die();

$lang = $sl->fn->lang([
    'Форма обратной связи',
    'Все поля с * обязательны к заполнению.',
    'Имя',
    'Email',
    'Название темы',
    'Сообшение'
]);
?>
<style>
.form_contact dd {
    display: block;
    margin: 0;
    padding: 3px 0;
}
.form_contact dd .sl_btn {
    margin-left: 0;
}
</style>
<h3><?=$lang[0]?></h3>
<form>
<?=$lang[1]?>
<dl class="form_contact">
	<dt><label><?=$lang[2]?><span class="star">&nbsp;*</span></label></dt>
	<dd><?=$sl->scin->input('name')?></dd>
    
    <dt><label><?=$lang[3]?><span class="star">&nbsp;*</span></label></dt>
	<dd><?=$sl->scin->input(['name'=>'email','type'=>'email'])?></dd>
    
    <dt><label><?=$lang[4]?><span class="star">&nbsp;*</span></label></dt>
	<dd><?=$sl->scin->input('subject')?></dd>
    
    <dt><label><?=$lang[5]?><span class="star">&nbsp;*</span></label></dt>
	<dd><?=$sl->scin->textarea('message','',['attr'=>['style'=>'margin: 0; height: 140px']])?></dd>

	<dd><?=$btn?></dd>
</dl>
</form>