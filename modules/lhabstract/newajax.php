<?php

$tpl = new erLhcoreClassTemplate( 'lhabstract/newajax.tpl.php');

$objectClass = 'erLhAbstractModel'.$Params['user_parameters']['identifier'];
$objectData = new $objectClass;

if (isset($Params['user_parameters_unordered']['attr']))
{
    $objectData->{$Params['user_parameters_unordered']['attr']} = urldecode($Params['user_parameters_unordered']['val']);
}

/*if ( isset($_POST['SaveClient']) ) {
	
	$Errors = erLhcoreClassAbstract::validateInput($objectData);	
    if (count($Errors) == 0)
    {   
        erLhcoreClassAbstract::getSession()->save($objectData);            
        erLhcoreClassModule::redirect('abstract/list/'.$Params['user_parameters']['identifier']);  
        exit;
    }  else {         
        $tpl->set('errArr',$Errors);
    }    
}*/

$tpl->set('object',$objectData);
$tpl->set('object_trans',$objectData->getModuleTranslations());
$tpl->set('identifier',$Params['user_parameters']['identifier']);

echo $tpl->fetch();

exit;