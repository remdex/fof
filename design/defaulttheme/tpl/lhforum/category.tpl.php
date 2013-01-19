<div class="header-list">
<?php include(erLhcoreClassDesign::designtpl('lhforum/search_box.tpl.php'));?> 
<h1><?=htmlspecialchars($category->name)?></h1>
</div>

<? if ($category->description != '') : ?>
<p class="cat-desc"><?=$category->description?></p><br />
<?endif;?>

<? 
$cache = CSCacheAPC::getMem();
$subcategorys = erLhcoreClassModelForumCategory::getList(array('filter' => array('parent' => $category->id),'disable_sql_cache' => true));
     
if (count($subcategorys) > 0) : ?>

<?php include(erLhcoreClassDesign::designtpl('lhforum/subcategory_list_points.tpl.php'));?>  

<?endif;?>


<? if ($pagesCurrent->items_total > 0) : ?>         
<? 
  $pages = $pagesCurrent;
  $topics = erLhcoreClassModelForumTopic::getList(array('filter' => array('path_'.$category->depth => $category->id),'offset' => $pagesCurrent->low, 'limit' => $pagesCurrent->items_per_page));
?>   

<?php include(erLhcoreClassDesign::designtpl('lhforum/topic_list.tpl.php'));?> 
           
<? endif; ?>

<?php if ( erLhcoreClassUser::instance()->isLogged() ) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhforum/new_topic.tpl.php'));?>
<?php endif;?>