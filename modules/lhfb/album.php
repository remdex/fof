<?php

$facebook = new Facebook(array(
  'appId'  => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'app_id' ),
  'secret' => erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'secret' ) ,
));

$userFB = $facebook->getUser();

if ($userFB) {
    $tpl = erLhcoreClassTemplate::getInstance( 'lhfb/album.tpl.php' );
    
    $album_id = $Params['user_parameters']['album_id'];
    
    $albumDetails = $facebook->api($album_id);
     
    $pages = new lhPaginator();
    $pages->items_total = $albumDetails['count'];
    $pages->serverURL = erLhcoreClassDesign::baseurl('fb/album').'/'.$album_id;
    $pages->paginate();
    
    $albumPhotos = $facebook->api($album_id . '/photos&limit=20&offset='.$pages->low);
    
    $tpl->set('photos',$albumPhotos);
    $tpl->set('album',$albumDetails);
    $tpl->set('pages',$pages);
    
    $Result['content'] = $tpl->fetch();
    
    $Result['path'] = array(
    array('url' => erLhcoreClassDesign::baseurl('user/index'),
    'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
    array('url' => erLhcoreClassDesign::baseurl('fb/albums'),
    'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Facebook albums')),
    array('title' => $albumDetails['name'])
    );
} else {
    $tpl = erLhcoreClassTemplate::getInstance( 'lhfb/login.tpl.php' );
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(
    array('url' => erLhcoreClassDesign::baseurl('user/index'),
    'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
    array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Facebook session has expired, please login')));
}