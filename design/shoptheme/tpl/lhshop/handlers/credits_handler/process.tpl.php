<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('credits_handler/process','Checkout');?></h1>
</div>
<?php if ($basket->order->amount_credits <= $basket->account_credits) : ?>
<form action="<?=erLhcoreClassDesign::baseurl('shop/process/credits_handler')?>" method="post">
<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('credits_handler/process','Checkout');?></p>
<input type="submit" name="ConfirmOrder" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('credits_handler/process','Confirm payment')?>" />
</form>
<?php else : ?>
<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('credits_handler/process','Not enouth credits to checkout');?></p>
<?php endif;?>