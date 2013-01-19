
<?php if ($mode == 'review') : ?>

<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('paypal_handler/review','Order payment confirmation');?></h1>
</div>

<?php
$payer_info = $resp_details->getPayerInfo();
$payer_id = $payer_info->getPayerID();

$paymentDetails = $resp_details->getPaymentDetails();//->getOrderTotal();
$OrderTotal = $paymentDetails->getOrderTotal(); 

$paymentAmount = $OrderTotal->_value;
$order_total = $currencyCodeType.' '.$paymentAmount;

?>

<form action="<?=erLhcoreClassDesign::baseurl('shop/reviewcredits/paypal_handler')?>/(token)/<?=$token?>/(paymentAmount)/<?=$paymentAmount?>/(currencyCodeType)/<?=$currencyCodeType?>/(paymentType)/<?=$paymentType?>/(payerID)/<?=$payer_id?>" method="post" name="ExpressCheckoutForm">
<table width=400>
<tr>
   <td><b><?=erTranslationClassLhTranslation::getInstance()->getTranslation('paypal_handler/review','Order amount');?>:</b></td>
   <td><?=$order_total?> (<?=$credits?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('paypal_handler/review','credits');?>)</td>
</tr>
</table>
<input type="submit" class="default-button" name="ConfirmPayment" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('paypal_handler/review','Confirm payment')?>" />
</form>

<?php else : ?>

<b><?=erTranslationClassLhTranslation::getInstance()->getTranslation('paypal_handler/review','Thank you for your payment!')?></b><br><br>


<?endif;?>