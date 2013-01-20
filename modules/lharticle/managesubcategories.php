<?php

$tpl = erLhcoreClassTemplate::getInstance('lharticle/managesubcategories.tpl.php');
$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);  

$pages = new lhPaginator();
$pages->serverURL = erLhcoreClassDesign::baseurl('article/managesubcategories').'/'.$Category->id;
$pages->items_total = erLhcoreClassModelArticle::getCount(array('filter' => array('category_id' => $Category->id)));
$pages->setItemsPerPage(10);
$pages->paginate();

$list = array();
if ($pages->items_total > 0) {
    $list = erLhcoreClassModelArticle::getList(array('filter' => array('category_id' => $Category->id),'offset' => $pages->low, 'limit' => $pages->items_per_page));
}

$tpl->set('category',$Category);
$tpl->set('list',$list);
$tpl->set('pages',$pages);
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => $Category->category_name));


$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/managecategories'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Manage categories')),

array('url' => erLhcoreClassDesign::baseurl('article/managecategories/').$Category->id,'title' => $Category->category_name)


)


?>