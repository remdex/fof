<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhabstract/listnewspaper.tpl.php');

$objectClass = 'erLhAbstractModel'.$Params['user_parameters']['identifier'];
$objectData = new $objectClass;

$pages = new lhPaginator();
$pages->items_total = call_user_func('erLhAbstractModel'.$Params['user_parameters']['identifier'].'::getCount',erLhcoreClassUser::getAdminFilter(array('newspaper_group_field' => $objectData->getNewspaperGroupField())));
$pages->translationContext = 'abstract/list';
$pages->serverURL = erLhcoreClassDesign::baseurl('abstract/listnewspaper').'/'.$Params['user_parameters']['identifier'];
$pages->setItemsPerPage(20);
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('identifier',$Params['user_parameters']['identifier']);
$tpl->set('object_trans',$objectData->getModuleTranslations());
$tpl->set('fields',$objectData->getFields());
$tpl->set('newspaper_gruop_field',$objectData->getNewspaperGroupField());



if ( method_exists($objectData,'defaultSort') ) {
    $tpl->set('sort',$objectData->defaultSort());
}

if ($objectData->hide_add === true) {
    $tpl->set('hide_add',true);
}

if ($objectData->hide_delete === true) {
    $tpl->set('hide_delete',true);
}



$Result['content'] = $tpl->fetch();
$Result['left_menu'] = 'abstract_newspaper';


?>