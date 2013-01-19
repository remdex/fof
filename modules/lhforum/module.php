<?php

$Module = array( "name" => "Forum",
                 'default_function' => 'index',
				 'variable_params' => true );

$ViewList = array();

$ViewList['admincategorys'] = array( 
    'script' => 'admincategorys.php',
    'params' => array('category_id'),    
    'functions' => array( 'administrate' ),
);

$ViewList['admintopic'] = array( 
    'script' => 'admintopic.php',
    'params' => array('topic_id'),    
    'functions' => array( 'administrate' ),
);
    
$ViewList['editcategory'] = array( 
    'script' => 'editcategory.php',
    'params' => array('category_id'),    
    'functions' => array( 'administrate' ),
    );
        
$ViewList['deleteusermessages'] = array( 
    'script' => 'deleteusermessages.php',
    'params' => array('user_id'),    
    'functions' => array( 'administrate' ),
);  
      
$ViewList['deleteusertopics'] = array( 
    'script' => 'deleteusertopics.php',
    'params' => array('user_id'),    
    'functions' => array( 'administrate' ),
);
                               
$ViewList['deletecategory'] = array( 
    'script' => 'deletecategory.php',
    'params' => array('category_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelForumCategory::isCategoryOwner','param' => 'category_id'),'global' => 'administrate'),
    ); 
                                   
$ViewList['deletetopic'] = array( 
    'script' => 'deletetopic.php',
    'params' => array('topic_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelForumTopic::isTopicOwner','param' => 'topic_id'),'global' => 'administrate'),
); 
                                  
$ViewList['edittopic'] = array( 
    'script' => 'edittopic.php',
    'params' => array('topic_id'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelForumTopic::isTopicOwner','param' => 'topic_id'),'global' => 'administrate'),
);
    
$ViewList['editmessage'] = array( 
    'script' => 'editmessage.php',
    'params' => array('msg_id'),
    'uparams' => array('action'),
    'functions' => array('edit_own_message'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelForumMessage::isMessageOwner','param' => 'msg_id'),'global' =>'administrate'),
);  

$ViewList['deletemessage'] = array( 
    'script' => 'deletemessage.php',
    'params' => array('msg_id'),
    'uparams' => array('action'),
    'limitations' => array('self' => array('method' => 'erLhcoreClassModelForumMessage::isMessageOwner','param' => 'msg_id'),'global' =>'administrate'),
);

$ViewList['createcategory'] = array( 
    'script' => 'createcategory.php',
    'params' => array('category_id'),
    'functions' => array( 'administrate' ),
);

$ViewList['index'] = array( 
    'script' => 'index.php',
    'params' => array(),
    'functions' => array( 'use_anonymous' )
);

$ViewList['quote'] = array( 
    'script' => 'quote.php',
    'params' => array('msg_id'),
    'functions' => array( 'use_anonymous' )
    );
    
$ViewList['category'] = array( 
    'script' => 'category.php',
    'params' => array('category_id'),
    'functions' => array( 'use_anonymous' )
    );
    
$ViewList['topic'] = array( 
    'script' => 'topic.php',
    'params' => array('topic_id'),
    'functions' => array( 'use_anonymous' )
    ); 
       
$ViewList['report'] = array( 
    'script' => 'report.php',
    'params' => array('msg_id'),
    'functions' => array( 'use_anonymous' )
    ); 
       
$ViewList['reportlist'] = array( 
    'script' => 'reportlist.php',
    'params' => array(),
    'uparams' => array('action','id'),
    'functions' => array( 'administrate' ),
); 
    
$ViewList['search'] = array( 
    'script' => 'search.php',
    'params' => array(),  
    'functions' => array( 'use_anonymous' ),
    'uparams' => array('keyword'),
);
         
$FunctionList = array();  
$FunctionList['use_anonymous'] = array('explain' => 'General permission to all users. Remove from anonymous users group to disble forum');
$FunctionList['use'] = array('explain' => 'General registered user permission [use]');
$FunctionList['administrate'] = array('explain' => 'Global edit permission [administrate]');
$FunctionList['edit_own_message'] = array('explain' => 'Permission to edit self messages');

?>