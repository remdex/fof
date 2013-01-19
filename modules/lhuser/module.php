<?php

$Module = array( "name" => "Users, groups management");

$ViewList = array();
   
$ViewList['login'] = array( 
    'script' => 'login.php',
    'params' => array(),
    'uparams' => array('d'),
    ); 
     
$ViewList['logout'] = array( 
    'script' => 'logout.php',
    'params' => array()
    );
         
$ViewList['loginwithgoogle'] = array( 
    'script' => 'loginwithgoogle.php',
    'params' => array()
    );  
          
$ViewList['mapaccounts'] = array( 
    'script' => 'mapaccounts.php',
    'params' => array()
    );
    
$ViewList['finishgoogleauth'] = array( 
    'script' => 'finishgoogleauth.php',
    'params' => array()
    );
    
$ViewList['account'] = array( 
    'script' => 'account.php',
    'params' => array(),
    'functions' => array( 'selfedit' )
    ); 
       
$ViewList['profilesettings'] = array( 
    'script' => 'profilesettings.php',
    'params' => array(),
    'functions' => array( 'selfedit' )
    );  
      
$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array(),    
    'functions' => array( 'selfedit' )
    );  
         
$ViewList['removeopenid'] = array( 
    'script' => 'removeopenid.php',
    'params' => array('open_id'),    
    'functions' => array( 'selfedit' ),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelOidMap::isOwner','param' => 'open_id'),'global' =>'administrate'),
    );  
      
$ViewList['userlist'] = array( 
    'script' => 'userlist.php',
    'params' => array(),
    'uparams' => array('email'),
    'functions' => array( 'userlist' ),
    );
          
$ViewList['grouplist'] = array( 
    'script' => 'grouplist.php',
    'params' => array(),
    'functions' => array( 'grouplist' ),
    );
              
$ViewList['manageprofile'] = array( 
    'script' => 'manageprofile.php',
    'params' => array('user_id'),
    'functions' => array( 'edituser' ),
);
    
$ViewList['edit'] = array( 
    'script' => 'edit.php',
    'params' => array('user_id'),
    'functions' => array( 'edituser' ),
    ); 
       
$ViewList['loginas'] = array( 
    'script' => 'loginas.php',
    'params' => array('user_id'),
    'functions' => array( 'edituser' ),
); 
       
$ViewList['delete'] = array( 
    'script' => 'delete.php',
    'params' => array('user_id'),
    'functions' => array( 'deleteuser' ),
);   
                
$ViewList['new'] = array( 
    'script' => 'new.php',
    'params' => array(),
    'functions' => array( 'createuser' ),
); 
           
$ViewList['newgroup'] = array( 
    'script' => 'newgroup.php',
    'params' => array(),
    'functions' => array( 'creategroup', 'editgroup' ),
    );
    
$ViewList['editgroup'] = array( 
    'script' => 'editgroup.php',
    'params' => array('group_id'),
    'functions' => array( 'editgroup' ),
    );     
    
$ViewList['groupassignuser'] = array( 
    'script' => 'groupassignuser.php',
    'params' => array('group_id'),
    'functions' => array( 'groupassignuser' ),
    ); 
    
$ViewList['deletegroup'] = array( 
    'script' => 'deletegroup.php',
    'params' => array('group_id'),
    'functions' => array( 'deletegroup' ),
    ); 
    
$ViewList['registration'] = array( 
    'script' => 'registration.php',
    'params' => array()
    );
    
$ViewList['registered'] = array( 
    'script' => 'registered.php',
    'params' => array()
    );
 
$ViewList['forgotpassword'] = array( 
    'script' => 'forgotpassword.php',
    'params' => array(),
    );

$ViewList['remindpassword'] = array( 
    'script' => 'remindpassword.php',
    'params' => array('hash'),
    );
     
$ViewList['profile'] = array( 
    'script' => 'profile.php',
    'params' => array('user_id'),
    ); 
    
$ViewList['completefblogin'] = array( 
    'script' => 'completefblogin.php',
    'params' => array(),
    );   
      
$ViewList['removefblogin'] = array( 
    'script' => 'removefblogin.php',
    'params' => array('user_id'),
    'functions' => array( 'selfedit' ),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelUserFB::isFBLoginOwner','param' => 'user_id'),'global' =>'administrate'),
);
      
$ViewList['associatedlogins'] = array( 
    'script' => 'associatedlogins.php',
    'params' => array(),
    'functions' => array( 'selfedit' ),
);

$ViewList['completetwitterlogin'] = array(
		'script' => 'completetwitterlogin.php',
		'params' => array(),
);

$ViewList['removetwitterlogin'] = array(
		'script' => 'removetwitterlogin.php',
		'params' => array('twitterlogin_id'),
		'functions' => array( 'selfedit' ),
		'limitations' => array('self' => array('method' => 'erLhcoreClassModelUserOauth::isTwitterLoginOwner','param' => 'twitterlogin_id'),'global' =>'administrate'),
);

$FunctionList = array();
$FunctionList['groupassignuser'] = array('explain' => 'Allow logged user to assing user to group');  
$FunctionList['editgroup'] = array('explain' => 'Allow logged user to edit group');  
$FunctionList['creategroup'] = array('explain' => 'Allow logged user to create group');  
$FunctionList['deletegroup'] = array('explain' => 'Allow logged user to delete group');  
$FunctionList['createuser'] = array('explain' => 'Allow logged user to create another user');  
$FunctionList['deleteuser'] = array('explain' => 'Allow logged user to delete another user');  
$FunctionList['edituser'] = array('explain' => 'Allow logged user to edit another user');  
$FunctionList['grouplist'] = array('explain' => 'Allow logged user to list group');  
$FunctionList['userlist'] = array('explain' => 'Allow logged user to list users');  
$FunctionList['selfedit'] = array('explain' => 'Allow logged user to edit his own data');  


?>