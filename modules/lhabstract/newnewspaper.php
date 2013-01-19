<?php

$tpl = new erLhcoreClassTemplate( 'lhabstract/newnewspaper.tpl.php');


$objectClass = 'erLhAbstractModel'.$Params['user_parameters']['identifier'];
$objectData = new $objectClass;

if ($objectData->can_create_newspaper !== true){
	erLhcoreClassModule::redirect('abstract/listnewspaper','/'.$Params['user_parameters']['identifier']);
	exit;
}

if (isset($_POST['CancelAction'])) {
    erLhcoreClassModule::redirect('abstract/listnewspaper','/'.$Params['user_parameters']['identifier']);  
    exit; 
}

if ( isset($_POST['SaveClient']) || isset($_POST['UpdateClient']) ) { 
	
	$Errors = erLhcoreClassAbstract::validateInput($objectData);	
	
    if (count($Errors) == 0)
    {     
        if ( method_exists($objectData,'saveThis') ) {
            $objectData->saveThis();
        } else {
            erLhcoreClassAbstract::getSession()->save($objectData); 
        }
        
        if (method_exists($objectData,'synchronizeAttribute')) {
            $objectData->synchronizeAttribute();
            erLhcoreClassAbstract::getSession()->update($objectData);
        }
        
        if ( isset($_POST['SaveClient']) ) {
	        erLhcoreClassModule::redirect('abstract/listnewspaper','/'.$Params['user_parameters']['identifier']);  
	        exit; 
        }
        
        if ( isset($_POST['UpdateClient']) ) {
        	erLhcoreClassModule::redirect('abstract/newspaperedit','/'.$Params['user_parameters']['identifier'].'/'.$objectData->id);
        	exit;
        }
        
    }  else {         
        $tpl->set('errors',$Errors);
    }    
}	

$tpl->set('object',$objectData);
$tpl->set('object_trans',$objectData->getModuleTranslations());
$tpl->set('identifier',$Params['user_parameters']['identifier']);

$Result['content'] = $tpl->fetch();

$Result['left_menu'] = 'abstract_newspaper';