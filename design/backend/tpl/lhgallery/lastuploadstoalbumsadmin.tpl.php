<div class="header-list">
<h1>
Last uploads to albums
</h1>
</div>

<? if ($pages->items_total > 0) { ?>         
  <? 
       $items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('sort' => 'addtime DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/album_list_last_uploads_admin.tpl.php'));?> 
      
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbum','No records.')?></p>

<? } ?>