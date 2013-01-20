<?php
$tpl = new erLhcoreClassTemplate( 'lharticle/category.tpl.php');

$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelArticle::getCount(array('filter' => array('category_id' => $Category->id)));
$pages->translationContext = 'article/subcategory';
$pages->serverURL = erLhcoreClassDesign::baseurl('article/category').'/'.$Category->id; 
$pages->setItemsPerPage(5);
$pages->paginate();

$list = array();
if ($pages->items_total > 0) {
    $list = erLhcoreClassModelArticle::getList(array('filter' => array('category_id' => $Category->id),'offset' => $pages->low, 'limit' => $pages->items_per_page));
}


$tpl->set('list',$list);
$tpl->set('pages',$pages);
$tpl->set('category',$Category);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => $Category->category_name));