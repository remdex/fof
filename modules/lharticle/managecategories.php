<?php

$tpl = erLhcoreClassTemplate::getInstance('lharticle/managecategories.tpl.php');

if (isset($_POST['CreateCategory']))
{    
        $CategoryNew = new erLhcoreClassModelArticleCategory();        
        $CategoryNew->category_name = $_POST['CategoryName'];
        $CategoryNew->position = $_POST['CategoryPos'];     
        $CategoryNew->parent = 0;                        
        erLhcoreClassArticle::getSession()->save($CategoryNew);  
        
        CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Manage categories'))
)

?>