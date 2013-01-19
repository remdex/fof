<?php

$Module = array( "name" => "Abstract table edit");

$ViewList = array();
   
$ViewList['new'] = array( 
    'script' => 'new.php',
    'functions' => array( 'use' ),
    'params' => array('identifier')
); 
   
$ViewList['newajax'] = array( 
    'script' => 'newajax.php',
    'functions' => array( 'use' ),
    'params' => array('identifier'),
    'uparams' => array('attr','val')
);  
  
$ViewList['saveajax'] = array( 
    'script' => 'saveajax.php',
    'functions' => array( 'use' ),
    'params' => array('identifier')
); 
  
$ViewList['uploadzip'] = array( 
    'script' => 'uploadzip.php',
    'functions' => array( 'use' ),
    'params' => array()
);   

$ViewList['list'] = array( 
    'script' => 'list.php',
    'functions' => array( 'use' ),
    'params' => array('identifier'),
    'uparams' => array('name')
);

$ViewList['listnewspaper'] = array( 
    'script' => 'listnewspaper.php',
    'functions' => array( 'use' ),
    'params' => array('identifier')
);

$ViewList['downloadbinnary'] = array( 
    'script' => 'downloadbinnary.php',
    'functions' => array( 'use' ),
    'params' => array('identifier','object_id')
);

$ViewList['edit'] = array( 
    'script' => 'edit.php',
    'functions' => array( 'use' ),
    'params' => array('identifier','object_id')
);

$ViewList['newnewspaper'] = array( 
    'script' => 'newnewspaper.php',
    'functions' => array( 'use' ),
    'params' => array('identifier','object_id')
);

$ViewList['newspaperedit'] = array( 
    'script' => 'newspaperedit.php',
    'functions' => array( 'use' ),
    'params' => array('identifier','object_id')
);
  
$ViewList['delete'] = array( 
    'script' => 'delete.php',
    'functions' => array( 'use' ),
    'params' => array('identifier','object_id')
); 

$ViewList['newspaperdelete'] = array(
		'script' => 'newspaperdelete.php',
		'functions' => array( 'use' ),
		'params' => array('identifier','object_id')
);

$ViewList['getauctionlocation'] = array( 
    'script' => 'getauctionlocation.php',
    'functions' => array( 'use' ),
    'params' => array('auction_id','selected_location')
);  

$ViewList['index'] = array( 
    'script' => 'index.php',
    'functions' => array( 'use' ),
    'params' => array()
); 

$ViewList['test'] = array( 
    'script' => 'test.php',
    'functions' => array( 'use' ),
    'params' => array()
); 

$FunctionList = array();
$FunctionList['use'] = array('explain' => 'Allow to use abstract module'); 

?>