<?php

try {
$Order = erLhcoreClassShop::getSession()->load( 'erLhcoreClassModelShopOrder', (int)$Params['user_parameters']['order_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

$Order->removeThis();

if ($Params['user_parameters']['user_id'] == null || !is_numeric($Params['user_parameters']['user_id']))
	erLhcoreClassModule::redirect('/shop/orderslist/');
else 
	erLhcoreClassModule::redirect('/shop/userorders/'.$Params['user_parameters']['user_id']);
exit;
    


?>