<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Category');?> - <?= $category !== false ? htmlspecialchars($category->name) : erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Home')?></h1>
</div>


<?php
$categoryID = $category !== false ? $category->cid : 0;
?>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys')?>/<?=$categoryID?>">
<?php
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('parent' => $categoryID)));
$pages->default_ipp = 8;
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$categoryID;
$pages->paginate();
if ($pages->items_total > 0) :?>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Title');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Owner');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>

<?php if (isset($pages)) : ?> 
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;

$cache = CSCacheAPC::getMem();
$categoryFilterID = $category !== false ? $category->cid : 0;
$filterParams = array('filter' => array('parent' => $categoryFilterID),'limit' => $pages->items_per_page,'offset' => $pages->low, 'cache_key' => 'version_'.$cache->getCacheVersion('category_'.$categoryFilterID));

foreach (erLhcoreClassModelGalleryCategory::getParentCategories($filterParams) as $categoryItem) : ?>
    <tr>
        <td width="1%"><?=$categoryItem->cid?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys')?>/<?=$categoryItem->cid?>"><?=htmlspecialchars($categoryItem->name)?></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$categoryItem->owner_id?>"><?=htmlspecialchars($categoryItem->owner)?></a></td>
        <td><input type="text" class="default-input" style="width:30px;" name="Position[]" value="<?=$categoryItem->pos?>" /><input type="hidden" name="CategoryIDs[]" value="<?=$categoryItem->cid?>" /></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/editcategory')?>/<?=$categoryItem->cid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Edit category');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Edit category');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/deletecategory')?>/<?=$categoryItem->cid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Delete category');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Delete category');?>" /></a></td>
    </tr>
<? endforeach; ?>

</table><br />
<input type="submit" class="default-button" name="UpdatePriority" value="Update priority" /> <a href="<?=erLhcoreClassDesign::baseurl('gallery/createcategory')?>/<?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Create a category');?></a>
</form>

<? endif;?>


<br />

<? 
if ($category !== false) :  ?>
<div class="header-list">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Category albums');?> <?= $category !== false ? ' - '.htmlspecialchars($category->name) : ' - '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Home')?></h3>
</div>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys')?>/<?=$categoryID?>">
<?
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true,'filter' => array('category' => $category->cid)));
$pages->default_ipp = 8;
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/managealbum').'/'.$category->cid;
$pages->paginate();

if ($pages->items_total > 0) :                   
                     
$items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
?>   
   
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_admin.tpl.php')); ?>

<?php else: ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','This category does not have an album.');?>
<br />

<?php endif; ?>
</form>
<?php endif;?> 


<? if ($category !== false) : ?>
<a href="<?=erLhcoreClassDesign::baseurl('gallery/createalbumadmin')?>/<?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Create an album');?></a> | <a href="<?=erLhcoreClassDesign::baseurl('gallery/createalbumadminbatch')?>/<?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Batch album create');?></a>
<?endif;?>