<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptioncredit','Payment option');?></h1>
</div>
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
<br />

<form action="<?=erLhcoreClassDesign::baseurl('shop/paymentoptioncredit')?>" method="post">
<?php
$currentUser = erLhcoreClassUser::instance(); 
$UserData = $currentUser->getUserData();
?>

<ul>
<?php foreach (erLhcoreClassShopPaymentHandler::getHandlers() as $handler) : 
if ($handler->active == true && $handler->allow_credit_buy == true) :
?>
<li><label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptioncredit',$handler->name);?> <input type="radio" name="CheckoutGateway" value="<?=$handler->identifier?>" /></label></li>
<?php endif; endforeach;?>
</ul>
<br />

<input type="submit" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoptioncredit','Continue');?>" name="ChoosePaymentMethod"/>
</form>

<br />




