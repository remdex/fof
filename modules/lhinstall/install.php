<?php

try {
	
$cfgSite = erConfigClassLhConfig::getInstance();

if ($cfgSite->getSetting( 'site', 'installed' ) == true)
{
    $Params['module']['functions'] = array('install');
    include_once('modules/lhkernel/nopermission.php'); 

    $Result['pagelayout'] = 'install';
    $Result['path'] = array(array('title' => 'High performance photo gallery install'));
    return $Result;

    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhinstall/install1.tpl.php');

switch ((int)$Params['user_parameters']['step_id']) {
    
	case '1':
		$Errors = array();		
		if (!is_writable("cache/cacheconfig/settings.ini.php"))
	       $Errors[] = "cache/cacheconfig/settings.ini.php is not writable";	
	              
		if (!is_writable("cache/translations"))
	       $Errors[] = "cache/translations is not writable"; 
	       	           
		if (!is_writable("cache/userinfo"))
	       $Errors[] = "cache/userinfo is not writable";
	          	           
		if (!is_writable("albums"))
	       $Errors[] = "albums is not writable";
	             	           
		if (!is_writable("albums/userpics"))
	       $Errors[] = "albums/userpics is not writable";
	              
		if (!is_writable("var/archives"))
	       $Errors[] = "var/archives is not writable";
	          	           
		if (!is_writable("var/tmpfiles"))
	       $Errors[] = "var/tmpfiles is not writable";
	             	           
		if (!is_writable("var/watermark"))
	       $Errors[] = "var/watermark is not writable";
	       	        	           
		if (!is_writable("var/forum"))
	       $Errors[] = "var/forum is not writable";	
	           
	       if (count($Errors) == 0)
	           $tpl->setFile('lhinstall/install2.tpl.php');	              
	  break;
	  
	  case '2':
		$Errors = array();	
			
		$definition = array(
            'DatabaseUsername' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
            'DatabasePassword' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
            'DatabaseHost' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
            'DatabasePort' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'int'
            ),
            'DatabaseDatabaseName' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'string'
            ),
        );
	     	       
	   $form = new ezcInputForm( INPUT_POST, $definition ); 
	      
	   
	   if ( !$form->hasValidData( 'DatabaseUsername' ) || $form->DatabaseUsername == '' )
       {
           $Errors[] = 'Please enter database username';
       }   
	   
	   if ( !$form->hasValidData( 'DatabasePassword' ) || $form->DatabasePassword == '' )
       {
           $Errors[] = 'Please enter database password';
       } 
       
	   if ( !$form->hasValidData( 'DatabaseHost' ) || $form->DatabaseHost == '' )
       {
           $Errors[] = 'Please enter database host';
       }  
       
	   if ( !$form->hasValidData( 'DatabasePort' ) || $form->DatabasePort == '' )
       {
           $Errors[] = 'Please enter database post';
       }
       
	   if ( !$form->hasValidData( 'DatabaseDatabaseName' ) || $form->DatabaseDatabaseName == '' )
       {
           $Errors[] = 'Please enter database name';
       }
       
       if (count($Errors) == 0)
       { 
           try {
           $db = ezcDbFactory::create( "mysql://{$form->DatabaseUsername}:{$form->DatabasePassword}@{$form->DatabaseHost}:{$form->DatabasePort}/{$form->DatabaseDatabaseName}" );
           } catch (Exception $e) {     
                  $Errors[] = 'Cannot login with provided logins. Returned message: <br/>'.$e->getMessage();
           }
       }
	    
	       if (count($Errors) == 0){
	           
	           $cfgSite = erConfigClassLhConfig::getInstance();
	           $cfgSite->setSetting( 'db', 'host', $form->DatabaseHost);
	           $cfgSite->setSetting( 'db', 'user', $form->DatabaseUsername);
	           $cfgSite->setSetting( 'db', 'password', $form->DatabasePassword);
	           $cfgSite->setSetting( 'db', 'database', $form->DatabaseDatabaseName);
	           $cfgSite->setSetting( 'db', 'port', $form->DatabasePort);
	           
	           $cfgSite->setSetting( 'site', 'secrethash', substr(md5(time() . ":" . mt_rand()),0,10));
	           
	           $cfgSite->save();
	                 
	           $tpl->setFile('lhinstall/install3.tpl.php');	
	       } else {
	           
	          $tpl->set('db_username',$form->DatabaseUsername);
	          $tpl->set('db_password',$form->DatabasePassword);
	          $tpl->set('db_host',$form->DatabaseHost);
	          $tpl->set('db_port',$form->DatabasePort);
	          $tpl->set('db_name',$form->DatabaseDatabaseName);
	          
	          $tpl->set('errors',$Errors);
	          $tpl->setFile('lhinstall/install2.tpl.php');	  
	       }           
	  break;

	case '3':
	    
	    $Errors = array();	

	    if ($_SERVER['REQUEST_METHOD'] == 'POST')
	    {	
    		$definition = array(
                'AdminUsername' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'string'
                ),
                'AdminPassword' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'string'
                ),
                'AdminPassword1' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'string'
                ),
                'AdminEmail' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
                )
            );
    	
    	    $form = new ezcInputForm( INPUT_POST, $definition ); 
    
    	        
    	    if ( !$form->hasValidData( 'AdminUsername' ) || $form->AdminUsername == '')
            {
                $Errors[] = 'Please enter admin username';
            }  
            
            if ($form->hasValidData( 'AdminUsername' ) && $form->AdminUsername != '' && strlen($form->AdminUsername) > 10)
            {
                $Errors[] = 'Maximum 10 characters for admin username';
            }
               
    	    if ( !$form->hasValidData( 'AdminPassword' ) || $form->AdminPassword == '')
            {
                $Errors[] = 'Please enter admin password';
            }    
            
    	    if ($form->hasValidData( 'AdminPassword' ) && $form->AdminPassword != '' && strlen($form->AdminPassword) > 10)
            {
                $Errors[] = 'Maximum 10 characters for admin password';
            }        
                    
    	    if ($form->hasValidData( 'AdminPassword' ) && $form->AdminPassword != '' && strlen($form->AdminPassword) <= 10 && $form->AdminPassword1 != $form->AdminPassword)
            {
                $Errors[] = 'Passwords missmatch';
            } 
           
                   
    	    if ( !$form->hasValidData( 'AdminEmail' ) )
            {
                $Errors[] = 'Wrong email address';
            } 
                                  
            if (count($Errors) == 0) {
                
               $tpl->set('admin_username',$form->AdminUsername);               
               if ( $form->hasValidData( 'AdminEmail' ) ) $tpl->set('admin_email',$form->AdminEmail);                     
    	      
    	        
    	       $db = ezcDbInstance::get();	       
    	                      
               //Groups table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_group` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) NOT NULL,
                  PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8;");
               
               // Administrators group
               $GroupData = new erLhcoreClassModelGroup();
               $GroupData->name    = "Administrators";
               erLhcoreClassUser::getSession()->save($GroupData);
               
               // Registered users group
               $GroupDataRegistered = new erLhcoreClassModelGroup();
               $GroupDataRegistered->name    = "Registered users";
               erLhcoreClassUser::getSession()->save($GroupDataRegistered);
               
               // Anonymous users group
               $GroupDataAnonymous = new erLhcoreClassModelGroup();
               $GroupDataAnonymous->name    = "Anonymous users group";
               erLhcoreClassUser::getSession()->save($GroupDataAnonymous);
                              
               // Roles table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_role` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) NOT NULL,
                  PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8;");
               
               // Administrators role
               $Role = new erLhcoreClassModelRole();
               $Role->name = 'Administrators';
               erLhcoreClassRole::getSession()->save($Role);
               
               // Registered users role
               $RoleRegistered = new erLhcoreClassModelRole();
               $RoleRegistered->name = 'Registered users';
               erLhcoreClassRole::getSession()->save($RoleRegistered);

               // Anonymous users role
               $RoleAnonymous = new erLhcoreClassModelRole();
               $RoleAnonymous->name = 'Anonymous users';
               erLhcoreClassRole::getSession()->save($RoleAnonymous);
               
               
               //Assing group to role table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_grouprole` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `group_id` int(11) NOT NULL,
                  `role_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `group_id` (`role_id`,`group_id`)
                ) DEFAULT CHARSET=utf8;");

               // Admin group assing admin role
               $GroupRole = new erLhcoreClassModelGroupRole();        
               $GroupRole->group_id =$GroupData->id;
               $GroupRole->role_id = $Role->id;        
               erLhcoreClassRole::getSession()->save($GroupRole);
        
               // Registered users role assign registered users role
               $GroupRoleRegistered = new erLhcoreClassModelGroupRole();        
               $GroupRoleRegistered->group_id = $GroupDataRegistered->id;
               $GroupRoleRegistered->role_id = $RoleRegistered->id;        
               erLhcoreClassRole::getSession()->save($GroupRoleRegistered);
               
               // Assign registered users anonymous users role
               $GroupRoleRegisteredAnonymous = new erLhcoreClassModelGroupRole();        
               $GroupRoleRegisteredAnonymous->group_id = $GroupDataRegistered->id;
               $GroupRoleRegisteredAnonymous->role_id = $RoleAnonymous->id;        
               erLhcoreClassRole::getSession()->save($GroupRoleRegisteredAnonymous);
                              
               // Anonymous users assing anonymous users role
               $GroupRoleAnonymous = new erLhcoreClassModelGroupRole();        
               $GroupRoleAnonymous->group_id = $GroupDataAnonymous->id;
               $GroupRoleAnonymous->role_id = $RoleAnonymous->id;        
               erLhcoreClassRole::getSession()->save($GroupRoleAnonymous);
               
               
               // Users
               $db->query("CREATE TABLE IF NOT EXISTS `lh_users` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `username` varchar(40) NOT NULL,
                      `password` varchar(40) NOT NULL,
                      `email` varchar(100) NOT NULL,
                      `lastactivity` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) DEFAULT CHARSET=utf8;");
               
               // Forgot password table
               $db->query("CREATE TABLE IF NOT EXISTS `lh_forgotpasswordhash` (
                      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                      `user_id` int(11) NOT NULL,
                      `hash` varchar(100) NOT NULL,
                      `created` int(11) NOT NULL,
                      PRIMARY KEY (`id`),
                      KEY `user_id` (`user_id`),
                      KEY `hash` (`hash`)
                    ) DEFAULT CHARSET=utf8;");
                              
                // Create admin user
                $UserData = new erLhcoreClassModelUser();
                $UserData->setPassword($form->AdminPassword);
                $UserData->email   = $form->AdminEmail;             
                $UserData->username = $form->AdminUsername;        
                erLhcoreClassUser::getSession()->save($UserData);

                // Create anonymous user
                $UserDataAnonymous = new erLhcoreClassModelUser();
                $UserDataAnonymous->setPassword(erLhcoreClassModelForgotPassword::randomPassword());
                $UserDataAnonymous->email   = $form->AdminEmail;             
                $UserDataAnonymous->username = 'anonymous';        
                erLhcoreClassUser::getSession()->save($UserDataAnonymous);                
                
                // User assign to groyp table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_groupuser` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `group_id` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `group_id` (`group_id`),
                  KEY `user_id` (`user_id`),
                  KEY `group_id_2` (`group_id`,`user_id`)
                )DEFAULT CHARSET=utf8;");

                // Assign admin user to admin group
                $GroupUser = new erLhcoreClassModelGroupUser();        
                $GroupUser->group_id = $GroupData->id;
                $GroupUser->user_id = $UserData->id;        
                erLhcoreClassUser::getSession()->save($GroupUser);
                
                // Assign Anonymous user to anonymous group
                $GroupUserAnonymous = new erLhcoreClassModelGroupUser();        
                $GroupUserAnonymous->group_id = $GroupDataAnonymous->id;
                $GroupUserAnonymous->user_id = $UserDataAnonymous->id;        
                erLhcoreClassUser::getSession()->save($GroupUserAnonymous);
                 
                //Assign default role functions
                $db->query("CREATE TABLE IF NOT EXISTS `lh_rolefunction` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `role_id` int(11) NOT NULL,
                  `module` varchar(100) NOT NULL,
                  `function` varchar(100) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `role_id` (`role_id`)
                )DEFAULT CHARSET=utf8;");
                                               
                // Albums table                 
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_albums` (
                  `aid` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `pos` int(11) NOT NULL DEFAULT '0',
                  `category` int(11) NOT NULL DEFAULT '0',
                  `keyword` varchar(50) DEFAULT NULL,
                  `owner_id` int(11) NOT NULL,
                  `public` int(11) NOT NULL DEFAULT '0',
                  `addtime` int(11) NOT NULL DEFAULT '0',
                  `album_pid` int(11) NOT NULL DEFAULT '0',
                  `hidden` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`aid`),
                  KEY `owner_id` (`owner_id`),
                  KEY `addtime` (`addtime`),
                  KEY `alb_category` (`category`,`hidden`,`pos`,`aid`),
                  KEY `aid` (`category`,`pos`,`aid`)
                )DEFAULT CHARSET=utf8;");
                
                // Categorys
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_categorys` (
                  `cid` int(11) NOT NULL AUTO_INCREMENT,
                  `owner_id` int(11) NOT NULL DEFAULT '0',
                  `name` varchar(255) NOT NULL DEFAULT '',
                  `description` text NOT NULL,
                  `pos` int(11) NOT NULL DEFAULT '0',
                  `parent` int(11) NOT NULL DEFAULT '0',
                  `hide_frontpage` int(11) NOT NULL,
                  `has_albums` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`cid`),
                  KEY `cat_parent` (`parent`),
                  KEY `cat_pos` (`pos`),
                  KEY `cat_owner_id` (`owner_id`)
                )DEFAULT CHARSET=utf8;");
                
                // Comments
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_comments` (
                          `pid` mediumint(10) NOT NULL DEFAULT '0',
                          `msg_id` mediumint(10) NOT NULL AUTO_INCREMENT,
                          `msg_author` varchar(25) NOT NULL DEFAULT '',
                          `msg_body` text NOT NULL,
                          `msg_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                          `msg_raw_ip` tinytext,
                          `msg_hdr_ip` tinytext,
                          `author_md5_id` varchar(32) NOT NULL DEFAULT '',
                          `author_id` int(11) NOT NULL DEFAULT '0',
                          `lang` varchar(5) NOT NULL,
                          PRIMARY KEY (`msg_id`),
                          KEY `com_pic_id` (`pid`)
                        ) DEFAULT CHARSET=utf8;"); 
                
                // Banned rate IP
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_images_rate_ban_ip` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `ip` varchar(39) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `ip` (`ip`)
                ) DEFAULT CHARSET=utf8;");
                
                // Protection against rating from same ip multiple times
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_images_rate_last_ip` (
                  `pid` int(11) NOT NULL,
                  `ip` varchar(39) NOT NULL,
                  PRIMARY KEY (`pid`)
                ) DEFAULT CHARSET=utf8;");
                
                // Banned IP
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_images_comment_ban_ip` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `ip` varchar(39) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `ip` (`ip`)
                ) DEFAULT CHARSET=utf8;");
                
                // Images table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_images` (
                          `pid` int(11) NOT NULL AUTO_INCREMENT,
                          `aid` int(11) NOT NULL DEFAULT '0',
                          `filepath` varchar(255) NOT NULL DEFAULT '',
                          `filename` varchar(255) NOT NULL DEFAULT '',
                          `filesize` int(11) NOT NULL DEFAULT '0',
                          `total_filesize` int(11) NOT NULL DEFAULT '0',
                          `pwidth` smallint(6) NOT NULL DEFAULT '0',
                          `pheight` smallint(6) NOT NULL DEFAULT '0',
                          `hits` int(10) NOT NULL DEFAULT '0',
                          `ctime` int(11) NOT NULL DEFAULT '0',
                          `owner_id` int(11) NOT NULL DEFAULT '0',
                          `pic_rating` int(11) NOT NULL DEFAULT '0',
                          `votes` int(11) NOT NULL DEFAULT '0',
                          `title` varchar(255) NOT NULL DEFAULT '',
                          `caption` text NOT NULL,
                          `keywords` varchar(255) NOT NULL DEFAULT '',
                          `pic_raw_ip` tinytext,
                          `approved` int(11) NOT NULL DEFAULT '0',
                          `mtime` int(11) NOT NULL,
                          `comtime` int(11) NOT NULL,
                          `has_preview` int(11) NOT NULL DEFAULT '0' COMMENT 'Used for video files',
                          `anaglyph` int(11) NOT NULL DEFAULT '0',
                          `rtime` int(11) NOT NULL,
                          `media_type` tinyint(4) NOT NULL,
                          PRIMARY KEY (`pid`),
                          KEY `owner_id` (`owner_id`),
                          KEY `pic_hits` (`hits`),
                          KEY `pic_rate` (`pic_rating`),
                          KEY `pic_aid` (`aid`),
                          KEY `mtime` (`mtime`),
                          KEY `pid_3` (`ctime`),
                          KEY `aid_4` (`aid`,`approved`,`pwidth`,`pheight`,`comtime`,`pid`),
                          KEY `approved` (`approved`,`pid`),
                          KEY `pid_12` (`pwidth`,`pheight`,`approved`,`pid`),
                          KEY `pid_4` (`approved`,`hits`,`pid`),
                          KEY `pid_4res` (`pwidth`,`pheight`,`approved`,`hits`,`pid`),
                          KEY `pid_5` (`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `pwidth_2` (`pwidth`,`pheight`,`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `pid` (`approved`,`mtime`,`pid`),
                          KEY `pwidth` (`pwidth`,`pheight`,`approved`,`mtime`,`pid`),
                          KEY `comtime` (`approved`,`comtime`,`pid`),
                          KEY `pid_com_res` (`pwidth`,`pheight`,`approved`,`comtime`,`pid`),
                          KEY `pid_7` (`aid`,`approved`,`hits`,`pid`),
                          KEY `pid_6` (`aid`,`approved`,`pid`),
                          KEY `pid_8` (`aid`,`approved`,`mtime`,`pid`),
                          KEY `pid_9` (`aid`,`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `pid_10` (`aid`,`approved`,`comtime`,`pid`),
                          KEY `aid` (`aid`,`pwidth`,`pheight`,`approved`,`pid`),
                          KEY `pid_2` (`ctime`,`approved`,`pid`),
                          KEY `pid_11` (`aid`,`pwidth`,`pheight`,`approved`,`hits`,`pid`),
                          KEY `aid_2` (`aid`,`pwidth`,`pheight`,`approved`,`mtime`,`pid`),
                          KEY `aid_3` (`aid`,`pwidth`,`pheight`,`approved`,`pic_rating`,`votes`,`pid`),
                          KEY `approved_2` (`approved`),
                          KEY `rated_gen` (`approved`,`rtime`,`pid`),
                          KEY `rated_gen_res` (`pwidth`,`pheight`,`approved`,`rtime`,`pid`),
                          KEY `a_rated_gen_res` (`aid`,`pwidth`,`pheight`,`approved`,`rtime`,`pid`),
                          KEY `a_rated_gen` (`aid`,`approved`,`rtime`,`pid`)
                        ) DEFAULT CHARSET=utf8;;");
                
                
                // Last search table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_lastsearch` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `countresult` int(11) NOT NULL,
                          `keyword` varchar(255) NOT NULL,
                          PRIMARY KEY (`id`)
                        ) DEFAULT CHARSET=utf8;"); 
                
                // Sharded index
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_shard_limit` (
                  `pid` int(11) NOT NULL DEFAULT '0',
                  `offset` int(11) NOT NULL DEFAULT '0',
                  `sort` varchar(40) NOT NULL,
                  `filter` varchar(40) NOT NULL,
                  `identifier` varchar(50) NOT NULL,
                  PRIMARY KEY (`offset`,`sort`,`filter`,`identifier`),
                  KEY `identifier` (`identifier`)
                ) DEFAULT CHARSET=utf8;");
                
                
                
                // Pallete table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_pallete` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `red` int(11) NOT NULL DEFAULT '0',
                  `green` int(11) NOT NULL DEFAULT '0',
                  `blue` int(11) NOT NULL DEFAULT '0',
                  `position` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`),
                  KEY `position` (`position`)
                ) AUTO_INCREMENT=121;");
                
                
                // Standard pallete colors
                $db->query("INSERT INTO `lh_gallery_pallete` (`id`, `red`, `green`, `blue`, `position`) VALUES
                (1, 213, 245, 254, 20),
                (2, 174, 235, 253, 40),
                (3, 136, 225, 251, 60),
                (4, 108, 213, 250, 80),
                (5, 89, 175, 213, 100),
                (6, 76, 151, 177, 120),
                (7, 60, 118, 140, 140),
                (8, 43, 85, 100, 160),
                (9, 32, 62, 74, 180),
                (10, 255, 255, 255, 200),
                (11, 247, 247, 247, 400),
                (12, 215, 230, 253, 220),
                (13, 176, 205, 252, 240),
                (14, 132, 179, 252, 260),
                (15, 90, 152, 250, 280),
                (16, 55, 120, 250, 300),
                (17, 49, 105, 209, 320),
                (18, 38, 83, 165, 340),
                (19, 28, 61, 120, 360),
                (20, 20, 43, 86, 380),
                (21, 205, 200, 252, 420),
                (22, 153, 139, 250, 440),
                (23, 101, 79, 249, 460),
                (24, 64, 50, 230, 480),
                (25, 54, 38, 175, 500),
                (26, 39, 31, 144, 520),
                (27, 32, 25, 116, 540),
                (28, 21, 18, 82, 560),
                (29, 16, 13, 61, 580),
                (30, 228, 228, 228, 600),
                (31, 224, 200, 252, 620),
                (32, 194, 144, 251, 640),
                (33, 165, 87, 249, 660),
                (34, 142, 57, 239, 680),
                (35, 116, 45, 184, 700),
                (36, 92, 37, 154, 720),
                (37, 73, 29, 121, 740),
                (38, 53, 21, 88, 760),
                (39, 37, 15, 63, 780),
                (40, 203, 203, 203, 800),
                (41, 238, 210, 226, 820),
                (42, 224, 162, 197, 840),
                (43, 210, 112, 166, 860),
                (44, 199, 62, 135, 880),
                (45, 159, 49, 105, 900),
                (46, 132, 41, 89, 920),
                (47, 104, 32, 71, 940),
                (48, 75, 24, 51, 960),
                (49, 54, 15, 36, 980),
                (50, 175, 175, 175, 1000),
                (51, 244, 216, 218, 1020),
                (52, 235, 175, 176, 1040),
                (53, 227, 133, 135, 1060),
                (54, 219, 87, 88, 1080),
                (55, 215, 50, 36, 1100),
                (56, 187, 25, 7, 1120),
                (57, 149, 20, 6, 1140),
                (58, 107, 14, 4, 1160),
                (59, 77, 9, 3, 1180),
                (60, 144, 144, 144, 1200),
                (61, 247, 221, 214, 1220),
                (62, 241, 187, 171, 1240),
                (63, 234, 151, 126, 1260),
                (64, 229, 115, 76, 1280),
                (65, 227, 82, 24, 1300),
                (66, 190, 61, 15, 1320),
                (67, 150, 48, 12, 1340),
                (68, 107, 34, 8, 1360),
                (69, 79, 25, 6, 1380),
                (70, 113, 113, 113, 1400),
                (71, 250, 232, 213, 1420),
                (72, 245, 207, 169, 1440),
                (73, 240, 183, 122, 1460),
                (74, 236, 159, 74, 1480),
                (75, 234, 146, 37, 1500),
                (76, 193, 111, 28, 1520),
                (77, 152, 89, 22, 1540),
                (78, 110, 64, 16, 1560),
                (79, 80, 47, 12, 1580),
                (80, 82, 82, 82, 1600),
                (81, 251, 236, 213, 1620),
                (82, 247, 218, 170, 1640),
                (83, 244, 200, 124, 1660),
                (84, 241, 182, 77, 1680),
                (85, 239, 174, 44, 1700),
                (86, 196, 137, 34, 1720),
                (87, 154, 108, 27, 1740),
                (88, 111, 77, 19, 1760),
                (89, 80, 56, 14, 1780),
                (90, 54, 54, 54, 1800),
                (91, 254, 248, 221, 1820),
                (92, 254, 243, 187, 1840),
                (93, 253, 237, 153, 1860),
                (94, 253, 231, 117, 1880),
                (95, 254, 232, 85, 1900),
                (96, 242, 212, 53, 1920),
                (97, 192, 169, 42, 1940),
                (98, 138, 120, 30, 1960),
                (99, 101, 87, 22, 1980),
                (100, 29, 29, 29, 2000),
                (101, 250, 248, 220, 2020),
                (102, 247, 243, 185, 2040),
                (103, 243, 239, 148, 2060),
                (104, 239, 232, 111, 2080),
                (105, 235, 229, 76, 2100),
                (106, 208, 200, 55, 2120),
                (107, 164, 157, 43, 2140),
                (108, 118, 114, 31, 2160),
                (109, 86, 82, 21, 2180),
                (110, 9, 9, 9, 2200),
                (111, 230, 237, 212, 2220),
                (112, 218, 232, 182, 2240),
                (113, 198, 221, 143, 2260),
                (114, 181, 210, 103, 2280),
                (115, 154, 186, 76, 2300),
                (116, 130, 155, 64, 2320),
                (117, 102, 121, 50, 2340),
                (118, 74, 88, 36, 2360),
                (119, 54, 64, 26, 2380),
                (120, 0, 0, 0, 2400);");
                
                // Images colors index
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_pallete_images` (
                  `pid` int(11) NOT NULL,
                  `pallete_id` smallint(3) NOT NULL,
                  `count` smallint(5) NOT NULL,
                  PRIMARY KEY (`pallete_id`,`pid`),
                  KEY `pid` (`pallete_id`,`count`,`pid`),
                  KEY `pallete_id` (`pallete_id`),
                  KEY `pid_2` (`pid`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_upload_archive` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `filename` varchar(200) NOT NULL,
				  `album_id` int(11) NOT NULL,
				  `album_name` varchar(100) NOT NULL,
				  `description` text NOT NULL,
				  `keywords` varchar(200) NOT NULL,
				  `user_id` int(11) NOT NULL DEFAULT '0',
				  `status` tinyint(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_user_fb` (
                  `user_id` int(11) NOT NULL,
                  `fb_user_id` bigint(20) NOT NULL,
                  `name` varchar(150) NOT NULL,
                  `link` varchar(250) NOT NULL,
                  PRIMARY KEY (`user_id`),
                  KEY `fb_user_id` (`fb_user_id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_oid_associations` (
                  `server_url` blob NOT NULL,
                  `handle` varchar(255) NOT NULL,
                  `secret` blob NOT NULL,
                  `issued` int(11) NOT NULL,
                  `lifetime` int(11) NOT NULL,
                  `assoc_type` varchar(64) NOT NULL,
                  PRIMARY KEY (`server_url`(100),`handle`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_oid_map` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `open_id` blob NOT NULL,
                  `user_id` int(11) NOT NULL,
                  `open_id_type` int(11) NOT NULL,
                  `email` varchar(100) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_oid_nonces` (
                  `server_url` varchar(2047) NOT NULL,
                  `timestamp` int(11) NOT NULL,
                  `salt` char(40) NOT NULL,
                  UNIQUE KEY `server_url` (`server_url`(100),`timestamp`,`salt`)
                ) DEFAULT CHARSET=utf8;");
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_myfavorites_images` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `session_id` int(11) NOT NULL,
				  `pid` int(11) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `session_id` (`session_id`)
				) DEFAULT CHARSET=utf8;");
                                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_myfavorites_session` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `session_hash` varchar(40) NOT NULL,
                  `mtime` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`),
                  KEY `session_hash` (`session_hash`)
                ) DEFAULT CHARSET=utf8;"); 
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_delay_image_hit` (
				  `pid` int(11) NOT NULL,
				  `mtime` int(11) NOT NULL,
				  KEY `pid` (`pid`)
				) DEFAULT CHARSET=utf8;");
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_popular24` (
                  `pid` int(11) NOT NULL,
                  `hits` int(11) NOT NULL,
                  `added` int(11) NOT NULL,
                  PRIMARY KEY (`pid`),
                  KEY `hits` (`hits`,`pid`),
                  KEY `added` (`added`)
                ) DEFAULT CHARSET=utf8;");
                               
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_rated24` (
                  `pid` int(11) NOT NULL,
                  `pic_rating` int(11) NOT NULL,
                  `votes` int(11) NOT NULL,
                  `added` int(11) NOT NULL,
                  PRIMARY KEY (`pid`),
                  KEY `pic_rating` (`pic_rating`,`votes`,`pid`),
                  KEY `added` (`added`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_collection` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `time` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_image` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `pid` int(11) NOT NULL,
				  `duplicate_collection_id` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_duplicate_image_hash` (
                  `pid` int(11) NOT NULL AUTO_INCREMENT,
                  `hash` varchar(40) NOT NULL,
                  PRIMARY KEY (`pid`),
                  KEY `hash` (`hash`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_filetypes` (
                  `extension` char(7) NOT NULL DEFAULT '',
                  `mime` char(254) DEFAULT NULL,
                  `content` char(15) DEFAULT NULL,
                  `player` varchar(5) DEFAULT NULL,
                  PRIMARY KEY (`extension`)
                ) DEFAULT CHARSET=utf8;");
                                
                $db->query("INSERT INTO `lh_gallery_filetypes` (`extension`, `mime`, `content`, `player`) VALUES
                ('jpg', 'image/jpg', 'image', 'IMAGE'),
                ('jpeg', 'image/jpeg', 'image', 'IMAGE'),
                ('jpe', 'image/jpe', 'image', 'IMAGE'),
                ('gif', 'image/gif', 'image', 'IMAGE'),
                ('png', 'image/png', 'image', 'IMAGE'),
                ('bmp', 'image/bmp', 'image', 'IMAGE'),
                ('jpc', 'image/jpc', 'image', 'IMAGE'),
                ('jp2', 'image/jp2', 'image', 'IMAGE'),
                ('jpx', 'image/jpx', 'image', 'IMAGE'),
                ('jb2', 'image/jb2', 'image', 'IMAGE'),
                ('swc', 'image/swc', 'image', 'IMAGE'),
                ('iff', 'image/iff', 'image', 'IMAGE'),
                ('psd', 'image/psd', 'image', 'IMAGE'),
                ('ogg', 'audio/ogg', 'audio', 'HTMLA'),
                ('oga', 'audio/ogg', 'audio', 'HTMLA'),
                ('ogv', 'video/ogg', 'movie', 'HTMLV'),
                ('mpg', 'video/mpeg','movie', 'VIDEO'),
                ('wmv', 'video/x-ms-wmv','movie', 'VIDEO'),
                ('mpeg','video/mpeg','movie', 'VIDEO'),
                ('mp4','video/mp4','movie', 'VIDEO'),
                ('avi', 'video/x-msvideo',  'movie',  'VIDEO'),
                ('swf', 'application/x-shockwave-flash', 'movie', 'SWF'),
                ('flv', 'video/x-flv', 'movie', 'FLV');");
                     
                // Create article module tables
                $db->query("CREATE TABLE IF NOT EXISTS `lh_article_static` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(200) NOT NULL,
				  `content` text NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;");
                
                $db->query("INSERT INTO `lh_article_static` (`id`, `name`, `content`) VALUES
				(1, 'Contact', '<p>\r\n	Contact information goes here</p>\r\n'),
				(2, 'Conditions', '<p>\r\n	Somes conditions goes here</p>\r\n'),
				(3, 'Gallery footer text', '<p>\r\n	&copy; 2010 <a href=\"lh:article/static/2\">Conditions</a> | <a href=\"lh:article/static/1\">Contact</a> | <a href=\"lh:feedback/form\">Feedback</a></p>\r\n');");

                // Create system configuration module tables
                $db->query("CREATE TABLE IF NOT EXISTS `lh_system_config` (
				  `identifier` varchar(50) NOT NULL,
				  `value` text NOT NULL,
				  `type` tinyint(1) NOT NULL DEFAULT '0',
				  `explain` varchar(250) NOT NULL,
				  `hidden` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`identifier`)
				) DEFAULT CHARSET=utf8;");
                
				$db->query("INSERT INTO `lh_system_config` (`identifier`, `value`, `type`, `explain`, `hidden`) VALUES
						('footer_article_id', 'a:3:{s:3:\"eng\";s:1:\"3\";s:3:\"lit\";s:2:\"28\";s:10:\"site_admin\";s:2:\"29\";}', 1, 'Footer article ID', 0),
						('max_photo_size', '5120', 0, 'Maximum photo size in kilobytes', 0),
						('max_archive_size', '20480', 0, 'Maximum archive size in kilobytes', 0),
						('file_queue_limit', '20', 0, 'How many files user can upload in single session', 0),
						('file_upload_limit', '200', 0, 'How many files upload during one session', 0),
						('thumbnail_width_x', '120', 0, 'Small thumbnail width - x', 0),
						('thumbnail_width_y', '130', 0, 'Small thumbnail width - Y', 0),
						('max_comment_length',  '1000',  '0',  'Maximum comment length',  '0'),
						('forum_photo_width',  '500',  '0',  'Forum photo width',  '0'),
						('forum_photo_height',  '500',  '0',  'Forum photo height',  '0'),
						('posts_per_page',  '20',  '0',  'How many post messages show per page',  '0'),
						('minimum_post_to_hot',  '20',  '0',  'How many post to became hot topic',  '0'),
						('video_convert_command',  'ffmpeg -y -i {original_file} -qmax 15 -s 580x440 -ar 22050 -ab 32 -f flv {converted_file} &> /dev/null',  '0',  '',  '0'),
						('flash_screenshot_command',  'bin/shell/xvfb-run.sh --server-args=\"-screen 0, 1024x2730x24\" bin/shell/screenshot.sh',  '0',  'Command witch is executed for making flash screenshot',  '0'),
						('allowed_file_types', '''jpg'',''gif'',''png'',''png'',''bmp'',''ogv'',''swf'',''flv'',''mpeg'',''avi'',''mpg'',''wmv''', 0, 'List of allowed file types to upload', 0),
						('normal_thumbnail_width_x', '400', 0, 'Normal size thumbnail width - x', 0),
						('normal_thumbnail_width_y', '400', 0, 'Normal size thumbnail width - y', 0),
						('loop_video', '0', 0, 'Should HTML5 video be looped? (1 - yes,0 - no))', 0),
						('thumbnail_scale_algorithm', 'croppedThumbnail', 0, 'It can be \"scale\" or \"croppedThumbnail\" - makes perfect squares, or \"croppedThumbnailTop\" makes perfect squares, image cropped from top', 0),
						('google_analytics_token', '', 0, 'Google analytics API key', 0),
						('google_analytics_site_profile_id', '', 0, 'Google analytics site profile id', 0),
						('thumbnail_quality_default', '93', 0, 'Converted small thumbnail image quality', 0),
						('normal_thumbnail_quality', '93', 0, 'Converted normal thumbnail quality', 0),
						('watermark_data', 'a:9:{s:17:\"watermark_enabled\";b:0;s:21:\"watermark_enabled_all\";b:0;s:9:\"watermark\";s:0:\"\";s:6:\"size_x\";i:200;s:6:\"size_y\";i:50;s:18:\"watermark_disabled\";b:1;s:18:\"watermark_position\";s:12:\"bottom_right\";s:28:\"watermark_position_padding_x\";i:10;s:28:\"watermark_position_padding_y\";i:10;}', 0, 'Not shown public, editing is done in watermark module', 1),
						('full_image_quality', '93', 0, 'Full image quality', 0),
						('google_translate_api_key',  '',  '0',  'Google translate API key, can be obtained from https://code.google.com/apis/console/',  '0'),
						('popularrecent_timeout', '24', 0, 'Most popular images timeout in hours', 0),
						('ratedrecent_timeout', '24', 0, 'Recently images timeout in hours', 0);");


				// Shop module
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_base_setting` (
				  `identifier` varchar(100) NOT NULL,
				  `value` varchar(100) NOT NULL,
				  `explain` varchar(100) NOT NULL,
				  PRIMARY KEY (`identifier`)
				) DEFAULT CHARSET=utf8;");
				
				$db->query("INSERT INTO `lh_shop_base_setting` (`identifier`, `value`, `explain`) VALUES
				('credit_price', '0.65', 'Credit price'),
				('max_downloads', '2', 'How many downloads can be done using download URL'),
				('main_currency', 'EUR', 'Shop base currency');");

				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_basket_image` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `session_id` int(11) NOT NULL,
				  `pid` int(11) NOT NULL,
				  `variation_id` int(11) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `session_id` (`session_id`)
				) DEFAULT CHARSET=utf8;");
				
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_basket_session` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `session_hash_crc32` bigint(20) NOT NULL,
				  `session_hash` varchar(40) NOT NULL,
				  `mtime` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_image_variation` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `width` int(11) NOT NULL,
				  `height` int(11) NOT NULL,
				  `name` varchar(50) NOT NULL,
				  `credits` int(11) NOT NULL,
				  `position` int(11) NOT NULL DEFAULT '0',
				  `type` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
				
				
				$db->query("INSERT INTO `lh_shop_image_variation` (`id`, `width`, `height`, `name`, `credits`, `position`, `type`) VALUES
				(1, 800, 800, 'Small', 3, 20, 0),
				(3, 480, 480, 'Extra small', 1, 10, 0),
				(4, 1414, 1414, 'Medium', 4, 30, 0),
				(5, 1825, 1825, 'Large', 5, 40, 0),
				(6, 0, 0, 'Original', 11, 60, 1);");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_order` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_time` int(11) NOT NULL,
				  `user_id` int(11) NOT NULL,
				  `status` int(11) NOT NULL DEFAULT '0',
				  `basket_id` int(11) NOT NULL,
				  `email` varchar(100) NOT NULL,
				  `payment_gateway` varchar(100) NOT NULL,
				  `currency` varchar(3) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
				
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_order_item` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `order_id` int(11) NOT NULL,
				  `pid` int(11) NOT NULL,
				  `image_variation_id` int(11) NOT NULL,
				  `hash` varchar(40) NOT NULL,
				  `credit_price` decimal(10,4) NOT NULL,
				  `credits` int(11) NOT NULL,
				  `download_count` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_payment_setting` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `identifier` varchar(50) NOT NULL,
				  `param` varchar(50) NOT NULL,
				  `value` varchar(100) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `identifier` (`identifier`,`param`)
				) DEFAULT CHARSET=utf8;");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_user_credit` (
				  `user_id` int(11) NOT NULL,
				  `credits` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`user_id`)
				) DEFAULT CHARSET=utf8;");
								
				$db->query("CREATE TABLE IF NOT EXISTS `lh_shop_user_credit_order` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `credits` int(11) NOT NULL,
				  `status` int(11) NOT NULL,
				  `date` int(11) NOT NULL,
				  `payment_gateway` varchar(100) NOT NULL,
				  `currency` varchar(3) NOT NULL,
				  PRIMARY KEY (`id`)
				) DEFAULT CHARSET=utf8;");
				
				$db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_pending_convert` (
                  `pid` int(11) NOT NULL,
                  `status` tinyint(4) NOT NULL,
                  PRIMARY KEY (`pid`)
                ) DEFAULT CHARSET=utf8;");
								
				$db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_searchhistory` (
                  `keyword` varchar(100) NOT NULL,
                  `countresult` int(11) NOT NULL,
                  `last_search` int(11) NOT NULL,
                  `searches_done` int(11) NOT NULL,
                  PRIMARY KEY (`keyword`),
                  KEY `last_search` (`last_search`)
                ) DEFAULT CHARSET=utf8;");
				
                // Main search index table
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_sphinx_search` (
                  `id` int(11) NOT NULL,
                  `title` varchar(255) NOT NULL,
                  `caption` text NOT NULL,
                  `filename` varchar(255) NOT NULL,
                  `file_path` varchar(255) NOT NULL,
                  `mtime` int(11) NOT NULL,
                  `comtime` int(11) NOT NULL,
                  `rtime` int(11) NOT NULL,
                  `pic_rating` int(11) NOT NULL,
                  `votes` int(11) NOT NULL,
                  `pwidth` smallint(6) NOT NULL,
                  `pheight` smallint(6) NOT NULL,
                  `colors` text NOT NULL,
                  `text_index` text NOT NULL,
                  `hits` int(11) NOT NULL,
                  `pid` int(11) NOT NULL,
                  `text_index_length` mediumint(9) NOT NULL,
                  PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8;");
                
                // Sphinx delta table
                $db->query("CREATE TABLE `lh_gallery_images_delta` (
                `pid` INT NOT NULL ,
                PRIMARY KEY (  `pid` )
                );");

                               
                $db->query("CREATE TABLE  `lh_gallery_pallete_images_stats` (
                `pid` INT NOT NULL ,
                `colors` VARCHAR( 100 ) NOT NULL ,
                PRIMARY KEY (  `pid` )
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_face_data` (
                  `pid` int(11) NOT NULL,
                  `data` text NOT NULL,
                  `sphinx_data` varchar(255) NOT NULL,
                  PRIMARY KEY (`pid`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_gallery_last_index` (
                  `identifier` varchar(50) NOT NULL,
                  `value` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`identifier`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_users_profile` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `name` varchar(150) NOT NULL,
                  `surname` varchar(150) NOT NULL,
                  `intro` text NOT NULL,
                  `photo` varchar(100) NOT NULL,
                  `variations` text NOT NULL,
                  `filepath` varchar(200) NOT NULL,
                  `website` varchar(200) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`)
                ) DEFAULT CHARSET=utf8;");
                
                
                $db->query("INSERT INTO `lh_gallery_last_index` (`identifier`, `value`) VALUES
                ('image_index', 0),
                ('sphinx_index', 0),
                ('face_index', 0),
                ('imgseek_index', 0);");
                   
                
                // Forum tables
                $db->query("CREATE TABLE IF NOT EXISTS `lh_forum_category` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `description` varchar(200) NOT NULL,
                  `name` varchar(50) NOT NULL,
                  `placement` int(11) NOT NULL DEFAULT '0',
                  `parent` int(11) NOT NULL DEFAULT '0',
                  `user_id` int(11) NOT NULL,
                  `topic_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Static counter for performance',
                  `message_count` int(11) NOT NULL DEFAULT '0',
                  `last_topic_id` int(11) NOT NULL DEFAULT '0' COMMENT 'For performance we store last topic id with message',
                  PRIMARY KEY (`id`),
                  KEY `parent` (`parent`),
                  KEY `id` (`placement`,`id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_forum_file` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `name` varchar(100) NOT NULL,
                  `file_path` varchar(250) NOT NULL,
                  `file_size` int(11) NOT NULL,
                  `message_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `message_id` (`message_id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_forum_message` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `topic_id` int(11) NOT NULL,
                  `ctime` int(11) NOT NULL,
                  `content` text NOT NULL,
                  `user_id` int(11) NOT NULL,
                  `ip` varchar(39) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `topic_id` (`topic_id`),
                  KEY `user_id` (`user_id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_forum_message_delta` (
                  `id` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_forum_report` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `msg_id` int(11) NOT NULL,
                  `message` text NOT NULL,
                  `ctime` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `msg_id` (`msg_id`)
                ) DEFAULT CHARSET=utf8;");
                
                $db->query("CREATE TABLE IF NOT EXISTS `lh_forum_topic` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `ctime` int(11) NOT NULL DEFAULT '0',
                  `topic_name` varchar(200) NOT NULL,
                  `path_1` int(11) NOT NULL DEFAULT '0',
                  `path_2` int(11) NOT NULL,
                  `path_3` int(11) NOT NULL,
                  `user_id` int(11) NOT NULL,
                  `path_0` int(11) NOT NULL DEFAULT '0',
                  `message_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Static counter for performance',
                  `last_message_ctime` int(11) NOT NULL DEFAULT '0',
                  `topic_status` tinyint(1) NOT NULL DEFAULT '0',
                  `main_category_id` int(11) NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`),
                  KEY `path_1` (`path_1`,`last_message_ctime`,`id`),
                  KEY `path_0` (`path_0`,`last_message_ctime`,`id`),
                  KEY `path_3` (`path_3`,`last_message_ctime`,`id`),
                  KEY `path_2` (`path_2`,`last_message_ctime`,`id`)
                )DEFAULT CHARSET=utf8;");
                                                 
                $RoleFunction = new erLhcoreClassModelRoleFunction();
                $RoleFunction->role_id = $Role->id;
                $RoleFunction->module = '*';
                $RoleFunction->function = '*';                
                erLhcoreClassRole::getSession()->save($RoleFunction);
                                
                $RoleFunctionRegistered = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegistered->role_id = $RoleRegistered->id;
                $RoleFunctionRegistered->module = 'lhuser';
                $RoleFunctionRegistered->function = 'selfedit';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegistered);
                
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhgallery';
                $RoleFunctionRegisteredGallery->function = 'use';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhfb';
                $RoleFunctionRegisteredGallery->function = 'use_registered';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                                
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhgallery';
                $RoleFunctionRegisteredGallery->function = 'personal_albums';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                
                // Auto approvement for registered users           
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhgallery';
                $RoleFunctionRegisteredGallery->function = 'auto_approve';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                
                // Forum write permission for users
                $RoleFunctionRegisteredGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionRegisteredGallery->role_id = $RoleRegistered->id;
                $RoleFunctionRegisteredGallery->module = 'lhforum';
                $RoleFunctionRegisteredGallery->function = 'use';                
                erLhcoreClassRole::getSession()->save($RoleFunctionRegisteredGallery);
                                
                // Some basic functions for anonymous forum usres
                $RoleFunctionAnonymousGallery = new erLhcoreClassModelRoleFunction();
                $RoleFunctionAnonymousGallery->role_id = $RoleAnonymous->id;
                $RoleFunctionAnonymousGallery->module = 'lhforum';
                $RoleFunctionAnonymousGallery->function = 'use_anonymous';                
                erLhcoreClassRole::getSession()->save($RoleFunctionAnonymousGallery);
                                
                $CategoryData = new erLhcoreClassModelGalleryCategory();
                $CategoryData->name = 'Users galleries';
                $CategoryData->hide_frontpage = 1;
                $CategoryData->owner_id = $UserData->id;
                erLhcoreClassGallery::getSession()->save($CategoryData); 
                 
                $cfgSite = erConfigClassLhConfig::getInstance();
	            $cfgSite->setSetting( 'gallery_settings', 'default_gallery_category', $CategoryData->cid);	     
	            $cfgSite->setSetting( 'site', 'installed', true);	     
	            $cfgSite->setSetting( 'user_settings', 'default_user_group', $GroupDataRegistered->id);	     
	            $cfgSite->setSetting( 'user_settings', 'anonymous_user_id', $UserDataAnonymous->id);	     
	            $cfgSite->save();
	           
    	        $tpl->setFile('lhinstall/install4.tpl.php');
    	       
            } else {      
                
               $tpl->set('admin_username',$form->AdminUsername);               
               if ( $form->hasValidData( 'AdminEmail' ) ) $tpl->set('admin_email',$form->AdminEmail);                      
    	          
    	       $tpl->set('errors',$Errors);
    	            
    	       
    	       $tpl->setFile('lhinstall/install3.tpl.php');
            }
	    } else {
	        $tpl->setFile('lhinstall/install3.tpl.php');
	    }
	    	
	    break;
	    
	case '4':
	    $tpl->setFile('lhinstall/install4.tpl.php');
	    break;
	    
	default:
	    $tpl->setFile('lhinstall/install1.tpl.php');
		break;
}

$Result['content'] = $tpl->fetch();
$Result['pagelayout'] = 'install';
$Result['path'] = array(array('title' => 'High performance photo gallery install'));

} catch (Exception $e){
	echo "Make sure that &quot;cache/*&quot; is writable and then <a href=\"".erLhcoreClassDesign::baseurl('install/install')."\">try again</a>";
}
?>