<?php

$Module = array( "name" => "System database configuration module");

$ViewList = array();
                
$ViewList['list'] = array( 
    'script' => 'list.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);
    
$ViewList['edit'] = array( 
    'script' => 'edit.php',
    'params' => array('config_id'),
    'functions' => array( 'administrate' )
);  
   
$ViewList['watermark'] = array( 
    'script' => 'watermark.php',
    'params' => array(),
    'functions' => array( 'administrate' )
); 
    
$FunctionList['administrate'] = array('explain' => 'Allow user to see configuration links');  

?>