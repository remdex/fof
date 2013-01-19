<?php

$part = (int)$Params['user_parameters']['part_id'];
$offset = (int)$part * erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','album_per_page');

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryAlbum' ); 
$q->orderBy('aid ASC' ); 
$q->where( 
       $q->expr->eq( 'hidden', $q->bindValue( 0 ) )
);
$q->limit(erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','album_per_page'),$offset);

$albums = $session->find( $q );  

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
      foreach ($albums as $album) {
        echo '<url><loc>';
        echo 'http://'.$_SERVER['HTTP_HOST']. $album->url_path;
        echo '</loc><changefreq>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','album_frequency').'</changefreq>
        <priority>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','album_priority').'</priority>
        </url>';
      }
echo '</urlset>';
exit;