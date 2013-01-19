<div class="header-list">
<?php include(erLhcoreClassDesign::designtpl('lhforum/search_box.tpl.php'));?> 
<h1>Forum</h1>
</div>

<?php 
$cache = CSCacheAPC::getMem();
$counter = 0;
foreach (erLhcoreClassModelForumCategory::getList(array('filter' => array('parent' => 0),'cache_key' => 'forum_version_'.$cache->getCacheVersion('forum_category_0'))) as $category) : ?>

<?php 
$subcategorys = erLhcoreClassModelForumCategory::getList(array('filter' => array('parent' => $category->id)));
if (count($subcategorys) > 0) : ?>
 <?php include(erLhcoreClassDesign::designtpl('lhforum/subcategory_list.tpl.php'));?> 
<?endif; ?>

<?php $counter++;endforeach;?>