<?php

try {
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['pid'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

try {
	$variation = erLhcoreClassModelShopImageVariation::fetch((int)$Params['user_parameters']['variation_id']);
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}


$favouriteSession = erLhcoreClassModelShopBasketSession::getInstance();

if (erLhcoreClassModelShopBasketImage::getImageCount(array('filter' => array('pid' => $Image->pid,'session_id'=> $favouriteSession->id,'variation_id' => $variation->id))) == 0)
{
	$imageBasket = new erLhcoreClassModelShopBasketImage();
	$imageBasket->session_id = $favouriteSession->id;
	$imageBasket->pid = $Image->pid;
	$imageBasket->variation_id = $variation->id;
	
	erLhcoreClassShop::getSession()->save($imageBasket);
	$favouriteSession->clearFavoriteCache();
}

echo json_encode(array('result' => 'ok'));
exit;

?>