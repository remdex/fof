<?php 

$imageVariation = erLhcoreClassShop::getSession()->load( 'erLhcoreClassModelShopImageVariation', (int)$Params['user_parameters']['variation_id']); 
$imageVariation->removeThis();
erLhcoreClassModule::redirect('shop/imagevariation');
return ;

?>