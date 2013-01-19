<?php

$totalImages = erLhcoreClassModelGalleryImage::getImageCount(array('filter' => array('approved' => 1)));
$totalParts = ceil($totalImages/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'));

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
      for ( $i = 0;$i < $totalParts; $i++ ) {
        echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/image').'/'. $i .'</loc></sitemap>';
      }
echo '</sitemapindex>';
exit;