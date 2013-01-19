<?php

try {
    $Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', $Params['user_parameters']['album_id'] );
    } catch (Exception $e){
        echo json_encode(array('result' => 'Album not found'));
        exit;
    }
    
$cache = CSCacheAPC::getMem(); 

$tpl = erLhcoreClassTemplate::getInstance( 'lhsystem/albumcacheinfo.tpl.php');
$tpl->set('album',$Album);

$tpl->set('album_version',$cache->getCacheVersion('album_'.$Album->aid));
$tpl->set('top_rated',$cache->getCacheVersion('top_rated_'.$Album->aid));
$tpl->set('last_commented',$cache->getCacheVersion('last_commented_'.$Album->aid));
$tpl->set('last_rated',$cache->getCacheVersion('last_rated_'.$Album->aid));

echo json_encode(array('result' => $tpl->fetch()));
exit;