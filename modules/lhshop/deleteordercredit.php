<?php

try {
$Order = erLhcoreClassShop::getSession()->load( 'erLhcoreClassModelShopUserCreditOrder', (int)$Params['user_parameters']['order_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

$Order->removeThis();

if ($Params['user_parameters']['user_id'] == null || !is_numeric($Params['user_parameters']['user_id']))
	erLhcoreClassModule::redirect('/shop/orderscreditslist/');
else 
	erLhcoreClassModule::redirect('/shop/userorderscredits/'.$Params['user_parameters']['user_id']);
exit;
    


?>