<?php

$part = (int)$Params['user_parameters']['part_id'];
$offset = (int)$part * erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','categorys_per_page');

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryCategory' ); 
$q->orderBy('cid ASC' ); 
$q->limit(erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','categorys_per_page'),$offset);

$categorys = $session->find( $q );  

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
      foreach ($categorys as $category) {
        echo '<url><loc>';
        echo 'http://'.$_SERVER['HTTP_HOST']. $category->path_url;
        echo '</loc><changefreq>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','category_frequency').'</changefreq>
        <priority>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','category_priority').'</priority>
        </url>';
      }
echo '</urlset>';
exit;