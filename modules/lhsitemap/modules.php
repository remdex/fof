<?php

$modulesURL = array();

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('/'),
    'changefreq' => 'daily',
    'priority' => '1',
    'lastmod' => date('Y-m-d'),
);

$dictionaries = erLhcoreClassModelWordDictionary::getList();

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

       foreach ($modulesURL as $moduleItem){
           echo '<url>
				<loc>http://'.$_SERVER['HTTP_HOST'].$moduleItem['url'].'</loc>
				<lastmod>'.$moduleItem['lastmod'].'</lastmod>
				<changefreq>'.$moduleItem['changefreq'].'</changefreq>
				<priority>'.$moduleItem['priority'].'</priority>
				</url>';
       }
       
       foreach ($dictionaries as $dictionary) {
           
            erLhcoreClassWord::$wordTable = $dictionary->tbl;
            $totalImages = erLhcoreClassModelWordWord::getCount(array('filter' => array('mf' => 1)));
             
            $totalParts = ceil($totalImages/120);
                     
            for ($i = 1; $i <= $totalParts; $i++)  {  
echo '<url>
				<loc>http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('dictionary/dictionary').'/'.$dictionary->dc.($i > 1 ? '/(page)/'.$i : '').'</loc>
				<lastmod>'.date('Y-m-d').'</lastmod>
				<changefreq>monthly</changefreq>
				<priority>0.9</priority>
				</url>';
            }
            
       }
       
echo '</urlset>';
exit;


exit;