<?php

$Module = array( "name" => "Facebook module");

$ViewList = array();
   
$ViewList['albums'] = array( 
    'script' => 'albums.php',
    'params' => array(),
    'functions' => array('use_registered')
); 

$ViewList['album'] = array( 
    'script' => 'album.php',
    'params' => array('album_id'),
    'functions' => array('use_registered')
); 
    
$ViewList['importfbphoto'] = array( 
    'script' => 'importfbphoto.php',
    'params' => array('album_id','image_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelGalleryAlbum::isAlbumOwner','param' => 'album_id'),'global' => 'administrate'),
    'functions' => array('use_registered')
);

$FunctionList = array();
$FunctionList['use_registered'] = array('explain' => 'Allow registered users import albums from facebook'); 

?>