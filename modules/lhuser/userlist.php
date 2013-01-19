<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/userlist.tpl.php');

$pages = new lhPaginator();

$filterParams = erLhcoreClassSearchHandler::getParams(array('module_file' => 'adminusers', 'format_filter' => true, 'use_override' => true, 'uparams' => $Params['user_parameters_unordered']));

$append = erLhcoreClassSearchHandler::getURLAppendFromInput($filterParams['input_form']); 
$pages->serverURL = erLhcoreClassDesign::baseurl('user/userlist').$append;
$pages->items_total = erLhcoreClassModelUser::getUserCount($filterParams['filter']);
$pages->setItemsPerPage(20);
$pages->paginate();

$userlist = erLhcoreClassModelUser::getUserList(array_merge(array('offset' => $pages->low, 'limit' => $pages->items_per_page,'sort' => 'email ASC'), $filterParams['filter']));

$tpl->set('userlist',$userlist);
$tpl->set('pages',$pages);
$tpl->set('filter',$filterParams['filter']);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','System configuration')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Users'))
)
?>