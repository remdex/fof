<div class="header-list">
<h1>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','Category');?> - <?= $category !== false ? htmlspecialchars($category->name) : erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','Home')?>
</h1>
</div>

<? if ($pages->items_total > 0) { ?>         
  <? 
       $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('category' => $category->cid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
  <form method="post" action="<?=erLhcoreClassDesign::baseurl('gallery/managealbum')?>/<?=$category->cid?>"> 
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_admin.tpl.php'));?> 
  </form>       
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','No records.')?></p>

<? } ?>



<div>
<a href="<?=erLhcoreClassDesign::baseurl('/gallery/createalbumadmin')?>/<?=$category->cid?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','Create an album');?></a>
</div>