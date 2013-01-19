<?php

$orderItemList = erLhcoreClassModelShopOrderItem::getList(array('filter' => array('hash' => $Params['user_parameters']['hash'])));

if (count($orderItemList) == 1) {
	$orderItem = array_shift($orderItemList);
	
	if ($orderItem->download_left > 0) {
			
		$image = $orderItem->image;
		$photoPath = 'albums/'.$image->filepath.$image->filename;		
		$imageAnalyzer = new ezcImageAnalyzer( $photoPath  ); 

			
		$image_variation = $orderItem->image_variation;		
		if ($image_variation->type == erLhcoreClassModelShopImageVariation::ORIGINAL_VARIATION) {
			$orderItem->download_count++;
			erLhcoreClassShop::getSession()->update($orderItem);
			header('Content-type: '.$imageAnalyzer->mime );
			echo file_get_contents($photoPath);			
		} else {
			
			$converterDownload = new ezcImageConverter(
                new ezcImageConverterSettings(
                    array( 
                        new ezcImageHandlerSettings( 'imagemagick', 'erLhcoreClassGalleryImagemagickHandler' ),
                        new ezcImageHandlerSettings( 'gd','erLhcoreClassGalleryGDHandler' )
                    )
                )
            );
            
            $converterDownload->createTransformation(
                'download_variation',
                array( 
                    new ezcImageFilter( 
                        'scale',
                        array( 
                            'width'     => (int)$image_variation->width, 
                            'height'    => (int)$image_variation->height,                            
                            'direction' => ezcImageGeometryFilters::SCALE_DOWN,
                        )
                    ),
                ),
                array( 
                    'image/jpeg',
                ),
                new ezcImageSaveOptions(array('quality' => (int)erLhcoreClassModelSystemConfig::fetch('full_image_quality')->current_value))
            );
            
            $orderItem->download_count++;
			erLhcoreClassShop::getSession()->update($orderItem);
			
            erLhcoreClassShop::getSession()->update($orderItem);
            header('Content-type: image/jpeg' );            
            $converterDownload->transform( 'download_variation',$photoPath, 'var/tmpfiles/'.$orderItem->hash.'.jpg'); 
            echo file_get_contents('var/tmpfiles/'.$orderItem->hash.'.jpg');
		}
		
		
				
	}
}

exit;