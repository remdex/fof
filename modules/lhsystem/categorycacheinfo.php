<?php


try {
    $Category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$Params['user_parameters']['category_id'] );
} catch (Exception $e){
    echo json_encode(array('result' => 'Category not found'));
    exit;
}

$cache = CSCacheAPC::getMem(); 

$tpl = erLhcoreClassTemplate::getInstance( 'lhsystem/categorycacheinfo.tpl.php');
$tpl->set('category',$Category);

$tpl->set('category_version',$cache->getCacheVersion('category_'.$Category->cid));


echo json_encode(array('result' => $tpl->fetch()));
exit;