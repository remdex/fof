<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhabstract/list.tpl.php');

$objectClass = 'erLhAbstractModel'.$Params['user_parameters']['identifier'];
$objectData = new $objectClass;

$append = '';
$filterParams['filter'] = array();
if ( isset($objectData->has_filter) &&  $objectData->has_filter === true ) {
	$filterParams = erLhcoreClassSearchHandler::getParams(array('module_file' => erLhAbstractModelNewspaper::FILTER_NAME, 'format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));
	$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']);
	$tpl->set('filter',erLhAbstractModelNewspaper::FILTER_NAME);	
}

$pages = new lhPaginator();
$pages->items_total = call_user_func('erLhAbstractModel'.$Params['user_parameters']['identifier'].'::getCount',$filterParams['filter']);
$pages->translationContext = 'abstract/list';
$pages->serverURL = erLhcoreClassDesign::baseurl('abstract/list').'/'.$Params['user_parameters']['identifier'].$append;
$pages->setItemsPerPage(20);
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('identifier',$Params['user_parameters']['identifier']);
$tpl->set('object_trans',$objectData->getModuleTranslations());
$tpl->set('fields',$objectData->getFields());
$tpl->set('filter_params',$filterParams['filter']);

if ( method_exists($objectData,'defaultSort') ) {
    $tpl->set('sort',$objectData->defaultSort());
}

$Result['content'] = $tpl->fetch();
$Result['left_menu'] = $objectData->left_menu == '' ? 'abstract' : $objectData->left_menu;


?>