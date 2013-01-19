<?php

$Module = array( "name" => 'Sitemap module', 'variable_params' => true );

$ViewList = array();
   
$ViewList['index'] = array (
    'params' => array(),
);
   
$ViewList['albumindex'] = array (
    'params' => array(),
);
    
$ViewList['album'] = array (
    'params' => array('part_id'),
);
    
$ViewList['categoryindex'] = array (
    'params' => array(),
);
    
$ViewList['category'] = array (
    'params' => array('part_id'),
);
  
$ViewList['imageindex'] = array (
    'params' => array(),
);
  
$ViewList['image'] = array (
    'params' => array('part_id'),
); 
 
$ViewList['words'] = array (
    'params' => array('dictionary','part_id'),
);
  
$ViewList['indexlanguage'] = array (
    'params' => array('access'),
);
  
$ViewList['modules'] = array (
    'params' => array(),
);

$FunctionList = array();