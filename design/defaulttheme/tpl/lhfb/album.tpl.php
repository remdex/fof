<div class="header-list">
<h1><?=htmlspecialchars($album['name'])?></h1>
</div>

<? if ($pages->items_total > 0) { ?> 
    <?php 
    $counter = 1;
    foreach ($photos['data'] as $photo) : 
    $namePhoto = isset($photo['name']) ? htmlspecialchars($photo['name']) : erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Preview version');
    ?>
        <div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?>">
            <div class="thumb-pic">
                <a target="_blank" href="<?=$photo['link']?>">            
                    <img title="<?=$namePhoto?>" src="<?=$photo['picture']?>" alt="<?=$namePhoto?>" width="120" height="130">
                </a>           
            </div>
            <div class="thumb-attr">
            
            <div class="tit-item">
                <h3><a target="_blank" title="<?=$namePhoto?>" href="<?=$photo['link']?>">
                    <?=$namePhoto?>      
                    </a>
                </h3>
            </div>
            
            <span class="res-ico"><?=$photo['width']?>x<?=$photo['height']?></span>                
            <span class="right"><input class="itemPhoto image_import" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Check photo to import')?>" type="checkbox" name="PhotoID[]" value="<?=$photo['id']?>" /></span>
            </div>
        </div>
    <?php $counter++;endforeach;?>
    
    <div style="clear:both;padding-bottom:10px;">
    
        <div class="header-list">
            <h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Import selected images to')?></h2>
        </div>

          <?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','To what album you want to import images, start typing album name and click search')?> <div><input type="text" value="" class="inputfield newAlbumName">&nbsp;<input type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Search for album')?>" class="default-button">&nbsp;<input type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Check all')?>" class="default-button" id="checkAllButton"> </div>
          <div id="album_select_directory0" style="padding-top:5px;padding-bottom:5px;">
          <ul>
          <?php 
          $user = erLhcoreClassUser::instance();
          $albums = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('owner_id' => $user->getUserID())));
          foreach ($albums as $album) : ?>
            <li><input type="radio" name="AlbumDestinationDirectory0" value="<?=$album->aid?>" /><a href="<?=$album->url_path?>" target="_blank" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Click to see target, new window will open')?>"><?=htmlspecialchars($album->title)?></a></li>
          <?php endforeach;?>
          </ul>
          </div>
          <input type="button" name="moveSelectedPhotos" <?php if (empty($albums)) : ?>style="display:none"<?php endif;?> id="moveAction" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fb/album','Import selected images')?>" class="default-button">  

          &nbsp;<span id="total_to_import"></span>
          
          <script>          
         var _lactq = _lactq || [];
        _lactq.push({'f':'hw_init_fb_album_import','a':[]});
          </script>
     </div>
     
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? } else { ?>
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','No records')?>.</p>
<? } ?>