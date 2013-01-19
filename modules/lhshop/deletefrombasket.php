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

if (erLhcoreClassModelShopBasketImage::getImageCount(array('filter' => array('pid' => $Image->pid,'session_id'=> $favouriteSession->id,'variation_id' => $variation->id))) == 1)
{	
	$objects = erLhcoreClassModelShopBasketImage::getImages(array('disable_sql_cache' => true,'filter' => array('pid' => $Image->pid,'session_id'=> $favouriteSession->id,'variation_id' => $variation->id)));
		
	foreach ($objects as $object)
	{
		$object->removeThis();
	}	
}

echo json_encode(array('result' => 'ok'));
exit;

?>