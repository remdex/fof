<?php

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/modules').'</loc></sitemap>';

$totalAlbums = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('filter' => array('hidden' => 0)));
$totalParts = ceil($totalAlbums/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','album_per_page'));
for ( $i = 0;$i < $totalParts; $i++ ) {
    echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/album').'/'. $i .'</loc></sitemap>';
}

$totalCategory = erLhcoreClassModelGalleryCategory::fetchCategoryColumn();
$totalParts = ceil($totalCategory/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','categorys_per_page'));
for ( $i = 0;$i < $totalParts; $i++ ) {
    echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/category').'/'. $i .'</loc></sitemap>';
}

$totalImages = erLhcoreClassModelGalleryImage::getImageCount(array('filter' => array('approved' => 1)));
$totalParts = ceil($totalImages/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'));
for ( $i = 0;$i < $totalParts; $i++ ) {
    echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/image').'/'. $i .'</loc></sitemap>';
}
      
echo '</sitemapindex>';
exit;