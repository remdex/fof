<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/buycredits','Buy credits')?></h1>
</div>
<p>
<form action="<?=erLhcoreClassDesign::baseurl('shop/buycredits')?>" method="post">

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/buycredits','How many credits do want to buy?');?></label>
<input type="text" class="inputfield" name="CreditsAmount" value="<?=$credits_order->credits?>" />
</div>

<input type="submit" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/buycredits','Choose payment method');?>" name="ChoosePaymentMethod"/>
</form>


</form>
</p>