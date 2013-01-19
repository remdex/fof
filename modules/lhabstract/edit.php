<?php

$tpl = erLhcoreClassTemplate::getInstance('lhabstract/edit.tpl.php');
$ObjectData = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModel'.$Params['user_parameters']['identifier'], (int)$Params['user_parameters']['object_id'] );

if (isset($_POST['CancelAction'])) {
    erLhcoreClassModule::redirect('abstract/list','/'.$Params['user_parameters']['identifier']);  
    exit; 
}

if (isset($_POST['SaveClient']) || isset($_POST['UpdateClient']))
{     
    $Errors = erLhcoreClassAbstract::validateInput($ObjectData);    
    if (count($Errors) == 0)
    {                             
        if ( method_exists($ObjectData,'updateThis') ) {
            $ObjectData->updateThis();
        } else {           
            erLhcoreClassAbstract::getSession()->update($ObjectData);  
        }    
        
        $cache = CSCacheAPC::getMem();
        $cache->increaseCacheVersion('site_attributes_version');
    
		if (isset($_POST['SaveClient'])){        
	        erLhcoreClassModule::redirect('abstract/list','/'.$Params['user_parameters']['identifier']);
	        exit;
		}
        
    }  else {
        $tpl->set('errors',$Errors);
    }
}


$tpl->set('object',$ObjectData);
$tpl->set('identifier',$Params['user_parameters']['identifier']);
$tpl->set('object_trans',$ObjectData->getModuleTranslations());

$Result['content'] = $tpl->fetch();

$Result['left_menu'] = $ObjectData->left_menu == '' ? 'abstract' : $ObjectData->left_menu;