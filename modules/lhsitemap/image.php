<?php

$part = (int)$Params['user_parameters']['part_id'];
$offset = (int)$part * erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page');

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 
$q->orderBy('pid ASC' ); 
$q->where( 
       $q->expr->eq( 'approved', $q->bindValue( 1 ) )
);
$q->limit(erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'),$offset);

$images = $session->find( $q );  

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
      foreach ($images as $image) {
        echo '<url><loc>';
        echo 'http://'.$_SERVER['HTTP_HOST']. $image->url_path;
        echo '</loc><changefreq>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_frequency').'</changefreq>
        <priority>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_priority').'</priority>
        </url>';
      }
echo '</urlset>';
exit;