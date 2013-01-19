<?php

try {
	$ObjectData = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModel'.$Params['user_parameters']['identifier'], (int)$Params['user_parameters']['object_id'] );
	
	if ( !$ObjectData->canEdit( erLhcoreClassUser::instance()->hasAccessTo('lhabstract','use') ) ) {
		erLhcoreClassModule::redirect('abstract/listnewspaper','/'.$Params['user_parameters']['identifier']);
		exit;
	}
	
} catch (Exception $e) {
	erLhcoreClassModule::redirect('/');
	exit;
}

$ObjectData->removeThis();
erLhcoreClassModule::redirect('abstract/listnewspaper','/'.$Params['user_parameters']['identifier']);
exit;