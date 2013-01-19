<?php
$tpl = new erLhcoreClassTemplate( 'lharticle/category.tpl.php');

$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelArticle::getArticlesBySearchCount('category_id',$Category->id);
$pages->translationContext = 'article/subcategory';
$pages->serverURL = 'article/subcategory/'.$Category->id;
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('category',$Category);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => $Category->category_name));
$Result['section_id'] = $Category->section_id;
$Result['category_id'] = $Category->id;
$Result['left_menu'] = 'leftmenu_specials.tpl.php';

$Result['top_banner_key'] = 'news_top_banner';
$Result['right_banner_key'] = 'news_right_banner';




?>