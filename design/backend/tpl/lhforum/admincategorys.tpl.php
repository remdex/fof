<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Category');?> - <?= $category !== false ? htmlspecialchars($category->name) : erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Home')?></h1>
</div>

<?php
$categoryID = $category !== false ? $category->id : 0;
?>
<form method="post" action="<?=erLhcoreClassDesign::baseurl('forum/admincategorys')?>/<?=$categoryID?>">
<?php
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelForumCategory::fetchCategoryColumn(array('filter' => array('parent' => $categoryID)));
$pages->default_ipp = 8;
$pages->serverURL = erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$categoryID;
$pages->paginate();
if ($pages->items_total > 0) :?>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Title');?></th>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Owner');?></th>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Topics');?></th>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Messages');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>

<?php if (isset($pages)) : ?> 
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;

$cache = CSCacheAPC::getMem();
$categoryFilterID = $category !== false ? $category->id : 0;
$filterParams = array('filter' => array('parent' => $categoryFilterID),'limit' => $pages->items_per_page,'offset' => $pages->low,'disable_sql_cache' => true);

foreach (erLhcoreClassModelForumCategory::getList($filterParams) as $categoryItem) : ?>
    <tr>
        <td width="1%"><?=$categoryItem->id?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('forum/admincategorys')?>/<?=$categoryItem->id?>"><?=htmlspecialchars($categoryItem->name)?></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$categoryItem->user_id?>"><?=htmlspecialchars($categoryItem->user)?></a></td>
        <td>
        <span class="msg-count">
        <?=$categoryItem->topic_count;?>
        </span>
        </td>
        <td>
        <span class="msg-count">
        <?=$categoryItem->message_count;?>
        </span>
        </td> 
        <td><input type="text" class="default-input" style="width:30px;" name="Position[]" value="<?=$categoryItem->placement?>" /><input type="hidden" name="CategoryIDs[]" value="<?=$categoryItem->id?>" /></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('forum/editcategory')?>/<?=$categoryItem->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Edit category');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Edit category');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('forum/deletecategory')?>/<?=$categoryItem->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Delete category');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Delete category');?>" /></a></td>
    </tr>
<? endforeach; ?>

</table><br />
<input type="submit" class="default-button" name="UpdatePriority" value="Update priority" /> <a href="<?=erLhcoreClassDesign::baseurl('forum/createcategory')?>/<?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Create a category');?></a>
</form>
<?php else : ?>
<a href="<?=erLhcoreClassDesign::baseurl('forum/createcategory')?>/<?=$categoryID?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Create a category');?></a>
<? endif;?>
</fieldset>


<? if (isset($pagesCurrent) && $pagesCurrent->items_total > 0) : ?>   
<br />
<br />
   
<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/admincategorys','Topics');?></h1>
</div>
   
  <? 
      $pages = $pagesCurrent;
      $topics = erLhcoreClassModelForumTopic::getList(array('filter' => array('path_'.$category->depth => $category->id),'offset' => $pagesCurrent->low, 'limit' => $pagesCurrent->items_per_page));
  ?>   

  <?php include(erLhcoreClassDesign::designtpl('lhforum/topic_list.tpl.php'));?> 
           
<? endif; ?>