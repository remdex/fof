<?php



$dictionary = erLhcoreClassModelWordDictionary::fetchByLanguage($Params['user_parameters']['dictionary']);

if ($dictionary !== false) {

    erLhcoreClassWord::$wordTable = $dictionary->tbl;
        
    $part = (int)$Params['user_parameters']['part_id'];
    $offset = (int)$part * erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page');
    
    $words = erLhcoreClassModelWordWord::getList(array('limit' => erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'), 'offset' => $offset, 'filter' => array('mf' => 1)));
    
       
    
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>  
          <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
          foreach ($words as $image) {
            echo '<url><loc>';
            echo 'http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('dictionary/word').'/'.$dictionary->dc.'/'.rawurlencode($image->word);
            echo '</loc><changefreq>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_frequency').'</changefreq>
            <priority>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_priority').'</priority>
            </url>';
          }
    echo '</urlset>';
}
exit;