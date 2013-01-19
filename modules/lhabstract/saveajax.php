<?php

$objectClass = 'erLhAbstractModel'.$Params['user_parameters']['identifier'];
$objectData = new $objectClass;

$Errors = erLhcoreClassAbstract::validateInput($objectData);	
if (count($Errors) == 0)
{     
    erLhcoreClassAbstract::getSession()->save($objectData);            
    echo json_encode(array('error' => 'false','result' => $objectData->__toString(),'result_id' => $objectData->id));            
} else {   
    $tpl = new erLhcoreClassTemplate( 'lhkernel/validation_error.tpl.php');      
    $tpl->set('errArr',$Errors);
    echo json_encode(array('error' => 'true','result' => $tpl->fetch()));
} 
    
exit;