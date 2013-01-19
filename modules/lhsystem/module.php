<?php

$Module = array( "name" => "System configuration");

$ViewList = array();
       
$ViewList['configuration'] = array( 
    'script' => 'configuration.php',
    'params' => array(),
    'functions' => array( 'use' )
    );  
           
$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array(),
    'functions' => array( 'use' )
    ); 
    
$ViewList['cachestatus'] = array( 
    'script' => 'cachestatus.php',
    'params' => array(),
    'functions' => array( 'use' )
);
         
$ViewList['expirecache'] = array( 
    'script' => 'expirecache.php',
    'params' => array(),
    'functions' => array( 'expirecache' )
);  
           
$ViewList['albumcacheinfo'] = array( 
    'script' => 'albumcacheinfo.php',
    'params' => array('album_id'),
    'functions' => array( 'use' )
    ); 
              
$ViewList['categorycacheinfo'] = array( 
    'script' => 'categorycacheinfo.php',
    'params' => array('category_id'),
    'functions' => array( 'use' )
    ); 
                 
$ViewList['clearimagecache'] = array( 
    'script' => 'clearimagecache.php',
    'params' => array('image_id'),
    'functions' => array( 'use' )
    ); 
    
$FunctionList['use'] = array('explain' => 'Allow user to see configuration links');  
$FunctionList['expirecache'] = array('explain' => 'Allow user to clear cache');  

?>