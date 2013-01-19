<?php

try{
    $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$Image->clearCache();
CSCacheAPC::getMem()->increaseCacheVersion('comments_'.$Image->pid);
CSCacheAPC::getMem()->increaseCacheVersion('last_commented');
CSCacheAPC::getMem()->increaseCacheVersion('last_commented_'.$Image->aid);
echo json_encode(array('error' => 'false'));
exit;