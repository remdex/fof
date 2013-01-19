<?php

try {
$OrderItem = erLhcoreClassShop::getSession()->load( 'erLhcoreClassModelShopOrderItem', (int)$Params['user_parameters']['order_item_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

$orderID = $OrderItem->order_id;
$OrderItem->removeThis();

erLhcoreClassModule::redirect('/shop/orderedit/'.$orderID);
exit;
    


?>