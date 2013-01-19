<?php

$Module = array( "name" => "Article module",
				 'variable_params' => true );

$ViewList = array();

$ViewList['static'] = array( 
    'script' => 'static.php',
    'params' => array('static_id')
    );    
           
$ViewList['staticlist'] = array( 
    'script' => 'staticlist.php',
    'params' => array(),    
    'functions' => array( 'edit' )
    );     
          
$ViewList['editstatic'] = array( 
    'script' => 'editstatic.php',
    'params' => array('static_id'),    
    'functions' => array( 'edit' )
    );
              
$ViewList['newstatic'] = array( 
    'script' => 'newstatic.php',
    'params' => array(),    
    'functions' => array( 'edit' )
    );

// Articles with categories
$ViewList['category'] = array(
		'script' => 'category.php',
		'params' => array('category_id')
);

$ViewList['subcategory'] = array(
		'script' => 'subcategory.php',
		'params' => array('category_id')
);

$ViewList['managesubcategories'] = array(
		'script' => 'managesubcategories.php',
		'params' => array('category_id'),
		'functions' => array( 'edit' )
);

$ViewList['view'] = array(
		'script' => 'view.php',
		'params' => array('article_id')
);

$ViewList['editcategory'] = array(
		'script' => 'editcategory.php',
		'params' => array('category_id'),
		'functions' => array( 'edit' )
);

$ViewList['deletearticle'] = array(
		'script' => 'deletearticle.php',
		'params' => array('article_id'),
		'functions' => array( 'edit' )
);

$ViewList['deletecategory'] = array(
		'script' => 'deletecategory.php',
		'params' => array('category_id'),
		'functions' => array( 'edit' )
);
 
$ViewList['editarticle'] = array(
		'script' => 'editarticle.php',
		'params' => array('article_id'),
		'functions' => array( 'edit' )
);

$ViewList['new'] = array(
		'script' => 'new.php',
		'params' => array('category_id'),
		'functions' => array( 'edit' )
);

$ViewList['section'] = array(
		'script' => 'section.php',
		'params' => array('section_id')
);
 
$ViewList['managecategories'] = array(
		'script' => 'managecategories.php',
		'params' => array(),
		'functions' => array( 'edit' )
);

$FunctionList = array();  
$FunctionList['edit'] = array('explain' => 'Allow edit articles');  

?>