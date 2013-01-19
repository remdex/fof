<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoption','Payment option');?></h1>
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

<form action="<?=erLhcoreClassDesign::baseurl('shop/paymentoption')?>" method="post">
<?php
$currentUser = erLhcoreClassUser::instance(); 
$UserData = $currentUser->getUserData();
?>
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoption','E-mail');?></label>
<input type="text" class="inputfield" name="Email" value="<?=$basket->order->email != '' ? htmlspecialchars($basket->order->email) : htmlspecialchars($UserData->email)?>" />
</div>
<br />

<ul>
<?php foreach (erLhcoreClassShopPaymentHandler::getHandlers() as $handler) : 
if ($handler->active == true) :
?>
<li><label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoption',$handler->name)?> <input type="radio" name="CheckoutGateway" value="<?=$handler->identifier?>" /></label></li>
<?php endif; endforeach;?>
</ul>
<br />

<input type="submit" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/paymentoption','Choose payment method');?>" name="ChoosePaymentMethod"/>
</form>

<br />




