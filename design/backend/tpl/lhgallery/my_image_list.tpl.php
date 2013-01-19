<div class="float-break">
<? 
$counter = 1;
$canApproveSelfImages = erLhcoreClassUser::instance()->hasAccessTo('lhgallery','can_approve_self_photos');

foreach ($items as $key => $item) : ?>
<div class="image-thumb<?=!(($counter) % 5) ? ' left-thumb' : ''?> thumb-edit" id="image_thumb_<?=$item->pid?>">

<?php if ($item->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE) : ?> 
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Rotate image clockwise');?>" class="rotate right" onclick="hw.rotateImage(<?=$item->pid?>,'rotate')"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Flip horizontaly');?>" class="switchicon right" onclick="hw.rotateImage(<?=$item->pid?>,'switch')"></a>
    <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Flip verticaly');?>" class="switchiconv right" onclick="hw.rotateImage(<?=$item->pid?>,'switchv')"></a>
<?php endif;?>
<a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Upload new image');?>" class="switchiconimg right" onclick="hw.setNewImage(<?=$item->pid?>)"></a>
<a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Set as folder thumbnail');?>" class="foldericon right" onclick="hw.setFolderImage(<?=$item->pid?>)"></a>


    <div class="left">
            <label><input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Select image');?>" class="itemPhoto" name="PhotoID[]" value="<?=$item->pid?>" /></label>
    </div>       
        
    <div class="thumb-pic" id="pid_thumb_<?=$item->pid?>">        
        <a href="<?=erLhcoreClassDesign::baseurl('gallery/editimage')?>/<?=$item->pid?>">        
        <?php include(erLhcoreClassDesign::designtpl('lhgallery/media_type_thumbnail.tpl.php')); ?>                
        </a>           
    </div>
    <div class="thumb-attr">
    
    <div class="tit-item">
        <h3><a title="<?=htmlspecialchars($item->name_user);?>" rel="<?=$item->url_path.$appendImageMode?>" href="<?=erLhcoreClassDesign::imagePath($item->filepath.$item->filename)?>">
            <?=($title = $item->name_user) == '' ? erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image_list','preview version') : $title;?>          
            </a>
        </h3>
    </div>
    
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Title')?></div>				
				<input type="text" id="PhotoTitle_<?=$item->pid?>" value="<?=htmlspecialchars($item->title)?>" class="inputfield" />

				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Keywords')?></div>	
				<input type="text" id="PhotoKeyword_<?=$item->pid?>" value="<?=htmlspecialchars($item->keywords)?>" class="inputfield" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Anaglyph')?></div>	
				<input type="checkbox" id="PhotoAnaglyph_<?=$item->pid?>" <?=$item->anaglyph == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Approved')?></div>	
				<input type="checkbox" id="PhotoApproved_<?=$item->pid?>" <?=$canApproveSelfImages == false ? 'disabled="disabled"' : ''?> <?=$item->approved == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
							
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Caption')?></div>			
				<textarea class="default-textarea" id="PhotoDescription_<?=$item->pid?>"><?=htmlspecialchars($item->caption)?></textarea>	  
    
    <div class="right">
        <a class="cursor" onclick="return hw.deletePhoto(<?=$item->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Delete image');?>" /></a>
    </div>               
    
    <input type="button" onclick="hw.updatePhoto(<?=$item->pid?>)"class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Update');?>" /><span class="status-img" id="image_status_<?=$item->pid?>"></span>            
    
    
    </div>
</div>
     
<?$counter++;endforeach; ?>  
</div>

<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>