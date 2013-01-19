<div class="float-break">

<? 
$counter = 1;
foreach ($items as $item) : 

?>
    <div class="album-thumb<?=!($counter % 4) ? ' left-thumb' : ''?>">
        <div class="content">        
            <div class="albthumb-img">
            <a href="<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages')?>/<?=$item->aid?>"><?php if ($item->album_thumb_path !== false) :?> 
            <img src="<?=erLhcoreClassDesign::imagePath($item->album_thumb_path)?>" alt="" width="130" height="140">
            <?php else :?>
            <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140">            
            <?php endif;?></a>      
            </div> 
                   
           <div class="tit-item">
           <h2><a title="<?=htmlspecialchars($item->title)?>" href="<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages')?>/<?=$item->aid?>"><?=htmlspecialchars($item->title)?></a></h2>      
           </div>
           
           <span class="files-ico" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list','files')?>">
            <?=$item->images_count;?>
           </span>
           
           <span class="right">
            <? if (date('Ymd') == date('Ymd',$item->addtime)) : ?>
                <?=date('H:i:s',$item->addtime)?>
            <?php else : ?>
                <?=date('Y-m-d',$item->addtime)?>
            <?php endif;?>
           </span>       
       </div>
       
       <div class="album-edit-items">
       <a href="<?=erLhcoreClassDesign::baseurl('gallery/addimagesadmin')?>/<?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Add images');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Add images');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('gallery/albumeditadmin')?>/<?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Edit album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Edit album');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('gallery/movealbumphotos')?>/<?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/move_photos.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move all album photos to another album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move all album photos to another album');?>" /></a>
       <a href="<?=erLhcoreClassDesign::baseurl('gallery/movebyresolution')?>/<?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/move_photos_resolution.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move photos by resolution');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Move photos by resolution');?>" /></a>

       <input title="Album priority" type="text" class="default-input" style="width:30px;" name="Position[]" value="<?=$item->pos?>" /><input type="hidden" name="AlbumIDs[]" value="<?=$item->aid?>" />
              
       <div class="right">
       <a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('gallery/deletealbumadmin')?>/<?=$item->aid?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Delete album');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Delete album');?>" /></a>
       </div>
       
       </div>
       
       
    </div>   
<?
$counter++;
endforeach; ?> 

</div>

<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<input type="submit" class="default-button" name="UpdatePriorityAlbum" value="Update priority" />


