<div class="header-list">

<div class="right">
<a href="<?=erLhcoreClassDesign::baseurl('gallery/addimagesadmin')?>/<?=$album->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Add images');?>" /></a>
<a href="<?=erLhcoreClassDesign::baseurl('gallery/albumeditadmin')?>/<?=$album->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Edit album');?>" /></a>
<a href="<?=erLhcoreClassDesign::baseurl('gallery/movealbumphotos')?>/<?=$album->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/move_photos.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move all album photos to another album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move all album photos to another album');?>" /></a>
<a href="<?=erLhcoreClassDesign::baseurl('gallery/movebyresolution')?>/<?=$album->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/move_photos_resolution.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move photos by resolution');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move photos by resolution');?>" /></a>
<a href="<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages')?>/<?=$album->aid?>/(action)/approve"><img src="<?=erLhcoreClassDesign::design('images/icons/eye.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Approve all images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Approve all images');?>" /></a>
<a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages')?>/<?=$album->aid?>/(action)/disapprove"><img src="<?=erLhcoreClassDesign::design('images/icons/eye_cross.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Disapprove all images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Disapprove all images');?>" /></a>
<a href="<?=$album->url_path_base?>" target="_blank"><img src="<?=erLhcoreClassDesign::design('images/icons/link.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','View on site');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','View on site');?>" /></a>
<a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('gallery/deletealbumadmin')?>/<?=$album->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Delete album');?>" /></a>
</div>
<?php
$urlAppendSort = '';
$urlSortBase  = erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$album->aid;
?>
<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/order_box.tpl.php'));?>
       
<h1><?=htmlspecialchars($album->title)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>
         
  <? 
            $items = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => 'albumlist_'.CSCacheAPC::getMem()->getCacheVersion('album_'.$album->aid),'use_index' => $use_index,'sort' => $modeSQL,'filter' => array('aid' => $album->aid),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
  
  <form action="" method="post">
  
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_image_list.tpl.php'));?> 
       

   
  <fieldset><legend>Change selected images album</legend> 
  <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','Move selected images to')?>
  <div><input type="text" class="default-input newAlbumName" value="" > <input class="default-button" type="button" value="Search album" /></div>
  
  <div id="album_select_directory0"></div>
   
  <input type="submit" class="default-button" value="Move photos" id="moveAction" style="display:none" name="moveSelectedPhotos" />  
  <input type="button" id="checkAllButton" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','Check all')?>" />  
  
  </fieldset>
  
  
  </form>
  
  
<script>
$('.newAlbumName').change(function(){	    
	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumnamesuggest')?>/0/"+escape($(this).val()), {} , function(data){	
                   $('#album_select_directory0').html(data.result);                       
                   if (data.error == 'false'){
                        $('#album_select_directory0 input').eq(0).attr("checked","checked");
                        $('#moveAction').show();
                   } else {
                       $('#moveAction').hide();
                   }                       
    	});	
});
$('#checkAllButton').click(function() { 
   $( '.itemPhoto' ).each( function() {         
		$( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
	})    
});
</script>
  
  
  
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','No records.')?></p>

<? } ?>

