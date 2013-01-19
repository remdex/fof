<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Checkout');?></h1>
</div>

<p>Checkout using mokejimai.lt system</p>

<form method="post" name="BankLinkForm" action="https://www.mokejimai.lt/pay/">
<input type="hidden" size="40" name="OrderID" id="orderIdField" value="<?=$payment_handler->basket->order->id?>">
<input type="hidden" size="40" name="Lang" value="LIT">
<input type="hidden" size="40" name="Amount" id="csketAmountField" value="<?=$payment_handler->basket->order->amount?>">
<input type="hidden" size="40" name="Currency" value="<?=$payment_handler->getSettingValue('currency')?>">
<input type="hidden" size="40" name="AcceptURL"  id="AcceptURLID" value="http://<?=$_SERVER['HTTP_HOST']?><?=erLhcoreClassDesign::baseurl('shop/accept/')?><?=$payment_handler->identifier?>">
<input type="hidden" size="40" name="CancelURL" value="http://<?=$_SERVER['HTTP_HOST']?><?=erLhcoreClassDesign::baseurl('shop/cancel/')?><?=$payment_handler->identifier?>">
<input type="hidden" size="40" name="CallbackURL" value="http://<?=$_SERVER['HTTP_HOST']?><?=erLhcoreClassDesign::baseurl('shop/callback/')?><?=$payment_handler->identifier?>" >
<input type="hidden" size="40" name="PayText" value="<?=$payment_handler->getSettingValue('pay_text')?>">
<input type="hidden" size="40" name="projectid" value="<?=$payment_handler->getSettingValue('projectid')?>">
<input type="hidden" id="IDSign" size="40" name="sign" value="<?=$payment_handler->handler->sign?>">

<?php if ($payment_handler->getSettingValue('test') == true) : ?>
<input type="hidden" size="40" name="test" value="1">
<?php endif;?>

<input size="30" type="hidden" class="inputfield" name="p_email" value="">

<input type="button" class="default-button" value="Checkout" />
   

</form>