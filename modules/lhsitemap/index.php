<?php

/*$totalAlbums = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('filter' => array('hidden' => 0)));
$totalCategory = erLhcoreClassModelGalleryCategory::fetchCategoryColumn();
$totalImages = erLhcoreClassModelGalleryImage::getImageCount(array('filter' => array('approved' => 1)));*/

$dictionaries = erLhcoreClassModelWordDictionary::getList();

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
     <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ( erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','siteaccess_sitemaps') as $access ) { 
        $accessUrl = '';
        $accessUrl = $access != erConfigClassLhConfig::getInstance()->getSetting('site','default_site_access') ? erLhcoreClassDesign::baseurldirect('/') . $access : '';

        echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. $accessUrl . erLhcoreClassDesign::baseurldirect('sitemap/modules').'</loc></sitemap>';

        foreach ($dictionaries as $dictionary) {
             erLhcoreClassWord::$wordTable = $dictionary->tbl;
             $totalImages = erLhcoreClassModelWordWord::getCount(array('filter' => array('mf' => 1)));
             
             $totalParts = ceil($totalImages/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'));
             for ( $i = 0;$i < $totalParts; $i++ ) {
                echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. $accessUrl . erLhcoreClassDesign::baseurldirect('sitemap/words').'/'.$dictionary->dc.'/'. $i .'</loc></sitemap>';
            }
        }
        
        /*$totalParts = ceil($totalAlbums/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','album_per_page'));
        for ( $i = 0;$i < $totalParts; $i++ ) {
            echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. $accessUrl . erLhcoreClassDesign::baseurldirect('sitemap/album').'/'. $i .'</loc></sitemap>';
        }
        
        
        $totalParts = ceil($totalCategory/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','categorys_per_page'));
        for ( $i = 0;$i < $totalParts; $i++ ) {
            echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. $accessUrl . erLhcoreClassDesign::baseurldirect('sitemap/category').'/'. $i .'</loc></sitemap>';
        }
        
        
        $totalParts = ceil($totalImages/erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'));
        for ( $i = 0;$i < $totalParts; $i++ ) {
            echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. $accessUrl . erLhcoreClassDesign::baseurldirect('sitemap/image').'/'. $i .'</loc></sitemap>';
        }*/        
        
    }
echo '</sitemapindex>';
exit;