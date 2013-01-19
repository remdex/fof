<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Edit')?></legend>

<? if (isset($errArr)) : ?>
<div class="error-list">
<ul>
<?php foreach ($errArr as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<br />
<? endif;?>

<? if (isset($data_updated)) : ?>
	<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Data updated');?></div>
<? endif; ?>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/shop/settingedit')?>/<?=$systemconfig->identifier?>">

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Identifier');?></label>
<input class="default-input" type="text" disabled="disabled" value="<?=htmlspecialchars($systemconfig->identifier);?>" />
</div>

<input class="default-input" type="text" name="ValueParam" value="<?=htmlspecialchars($systemconfig->value);?>" />

<input type="submit" class="default-button" name="UpdateConfig" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/edit','Update')?>"/>

</form>

</fieldset>