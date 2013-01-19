<?php

$Module = array( "name" => "Shop module");

$ViewList = array();
                
$ViewList['imagevariation'] = array( 
    'script' => 'imagevariation.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);
    
$ViewList['imagevariationedit'] = array( 
    'script' => 'imagevariationedit.php',
    'params' => array('variation_id'),
    'functions' => array( 'administrate' )
);     

$ViewList['imagevariationnew'] = array( 
    'script' => 'imagevariationnew.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);

$ViewList['imagevariationdelete'] = array( 
    'script' => 'imagevariationdelete.php',
    'params' => array('variation_id'),
    'functions' => array( 'administrate' )
); 

$ViewList['listpaymentoptions'] = array( 
    'script' => 'listpaymentoptions.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);

$ViewList['orderslist'] = array( 
    'script' => 'orderslist.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);

$ViewList['orderscreditslist'] = array( 
    'script' => 'orderscreditslist.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);

$ViewList['userorders'] = array( 
    'script' => 'userorders.php',
    'params' => array('user_id'),
    'functions' => array( 'administrate' )
);

$ViewList['userorderscredits'] = array( 
    'script' => 'userorderscredits.php',
    'params' => array('user_id'),
    'functions' => array( 'administrate' )
);

$ViewList['paymentoptionedit'] = array( 
    'script' => 'paymentoptionedit.php',
    'params' => array('identifier'),
    'functions' => array( 'administrate' )
); 

$ViewList['deleteorderitem'] = array( 
    'script' => 'deleteorderitem.php',
    'params' => array('order_item_id'),
    'functions' => array( 'administrate' )
); 

$ViewList['deleteorder'] = array( 
    'script' => 'deleteorder.php',
    'params' => array('order_id','user_id'),
    'functions' => array( 'administrate' )
);

$ViewList['deleteordercredit'] = array( 
    'script' => 'deleteordercredit.php',
    'params' => array('order_id','user_id'),
    'functions' => array( 'administrate' )
); 

$ViewList['settinglist'] = array( 
    'script' => 'settinglist.php',
    'params' => array(),
    'functions' => array( 'administrate' )
); 

$ViewList['settingedit'] = array( 
    'script' => 'settingedit.php',
    'params' => array('config_id'),
    'functions' => array( 'administrate' )
); 

$ViewList['orderedit'] = array( 
    'script' => 'orderedit.php',
    'params' => array('order_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelShopOrder::userOrderFetch','param' => 'order_id'),'global' =>'administrate'),
); 

$ViewList['addtobasket'] = array( 
    'script' => 'addtobasket.php',
    'params' => array('pid','variation_id')
); 

$ViewList['deletefrombasket'] = array( 
    'script' => 'deletefrombasket.php',
    'params' => array('pid','variation_id')
); 

$ViewList['basket'] = array( 
    'script' => 'basket.php',
    'params' => array()
); 

$ViewList['paymentoption'] = array( 
    'script' => 'paymentoption.php',    
    'functions' => array( 'use' ),
    'params' => array()
); 

$ViewList['paymentoptioncredit'] = array( 
    'script' => 'paymentoptioncredit.php',    
    'functions' => array( 'use' ),
    'params' => array()
); 

$ViewList['process'] = array( 
    'script' => 'process.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier')
);

$ViewList['error'] = array( 
    'script' => 'error.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier')
);

$ViewList['review'] = array( 
    'script' => 'review.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier'),
    'uparams' => array('token','paymentAmount','currencyCodeType','paymentType','payerID'),
);

$ViewList['reviewcredits'] = array( 
    'script' => 'reviewcredits.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier'),
    'uparams' => array('token','paymentAmount','currencyCodeType','paymentType','payerID'),
);

$ViewList['accept'] = array( 
    'script' => 'accept.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier')
);

$ViewList['cancel'] = array( 
    'script' => 'cancel.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier')
);

$ViewList['cancelcredits'] = array( 
    'script' => 'cancelcredits.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier')
);

$ViewList['myorders'] = array( 
    'script' => 'myorders.php',
    'functions' => array( 'use' ),
    'params' => array()
);

$ViewList['mycreditsorders'] = array( 
    'script' => 'mycreditsorders.php',
    'functions' => array( 'use' ),
    'params' => array()
);

$ViewList['processcredits'] = array( 
    'script' => 'processcredits.php',    
    'functions' => array( 'use' ),
    'params' => array('identifier')
); 

$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array(),
    'functions' => array( 'administrate' )
);

$ViewList['mycredits'] = array( 
    'script' => 'mycredits.php',
    'params' => array(),
    'functions' => array( 'use' )
); 
 
$ViewList['buycredits'] = array( 
    'script' => 'buycredits.php',
    'params' => array(),
    'functions' => array( 'use' )
); 

$ViewList['download'] = array( 
    'script' => 'download.php',
    'params' => array('hash')
); 

$ViewList['orderview'] = array( 
    'script' => 'orderview.php',
    'params' => array('order_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelShopOrder::userOrderFetch','param' => 'order_id'),'global' =>'administrate'),
);  
   
$FunctionList['administrate'] = array('explain' => 'Administration function');  
$FunctionList['use'] = array('explain' => 'Allow to use shop module');  


