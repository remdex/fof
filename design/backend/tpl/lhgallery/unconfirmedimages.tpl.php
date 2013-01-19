<div class="header-list"><h1>Unconfirmed images</h1></div>

<? if ($pages->items_total > 0) { ?>
         
  <? 
            $items = erLhcoreClassModelGalleryImage::getImages(array('filter' => array('approved' => 0),'offset' => $pages->low, 'limit' => $pages->items_per_page));
  ?>   
  
<form action="" method="post">
  
  <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/my_image_list.tpl.php'));?> 
       
  
  <fieldset><legend>Manipulate selected images</legend> 
  <?php if (isset($approved_count)) : ?>
  <div class="ok"><?=$approved_count?> images were approved.</div>
  <?php endif;?>
  
  <?php if (isset($remove_count)) : ?>
  <div class="ok"><?=$remove_count?> images were removed.</div>
  <?php endif;?>
  
  <input type="submit" class="default-button" value="Approve selected" name="approveSelected" />  
  <input type="submit" class="default-button" value="Remove selected" name="removeSelected" />  
  </fieldset>
 
  
  <fieldset><legend>Change selected images album</legend> 
  <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','Move selected images to')?>
  <div><input type="text" class="default-input newAlbumName" value="" > <input class="default-button" type="button" value="Search album" /></div>  
  <div id="album_select_directory0"></div>   
  <input type="submit" class="default-button" value="Move photos" id="moveAction" style="display:none" name="moveSelectedPhotos" />  
  </fieldset>
  
  <fieldset><legend>Check all</legend>
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

