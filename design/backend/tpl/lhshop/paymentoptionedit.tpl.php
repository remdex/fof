<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptionedit','Payment option edit')?></legend>

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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('shop/paymentoptionedit')?>/<?=$payment_handler->identifier?>">


<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptionedit','Identifier');?></label>
<input class="default-input" type="text" disabled="disabled" value="<?=htmlspecialchars($payment_handler->identifier);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptionedit','Active');?></label>
<input type="checkbox" name="ActiveHandler" <?=$payment_handler->active == true ? 'checked="checked"' : ''?> />
</div>

<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptionedit','Payment settings');?></legend> 
<?php foreach ($payment_handler->settings as $key => $setting) : ?>

<div class="in-blk">
<label><?=$setting['name']?></label>

<?php if ($setting['type'] == 'text') : ?>
	<input class="default-input" name="<?=$payment_handler->identifier?>_<?=$key?>" type="text" value="<?=htmlspecialchars($payment_handler->getSettingValue($key));?>" />
<?php elseif ($setting['type'] == 'checkbox') : ?>
	<input type="checkbox" value="on" name="<?=$payment_handler->identifier?>_<?=$key?>" <?=$payment_handler->getSettingValue($key) !== false && $payment_handler->getSettingValue($key)->value == '1' ? 'checked="checked"' : ''?> />
<?php endif;?>

</div>

<?php endforeach; ?>
</fieldset>
<br />

<input type="submit" class="default-button" name="UpdatePaymentSetting" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptionedit','Update')?>"/>

</form>

</fieldset>