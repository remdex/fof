<div class="image-full">
    <div class="image-full-content">
    
        <div class="header-list">
        
            <?php if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE) : ?> 
                <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Rotate image clockwise');?>" class="rotate right" onclick="hw.rotateImageFromEditWindow(<?=$image->pid?>,'rotate')"></a>
                <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Flip horizontaly');?>" class="switchicon right" onclick="hw.rotateImageFromEditWindow(<?=$image->pid?>,'switch')"></a>
                <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Flip verticaly');?>" class="switchiconv right" onclick="hw.rotateImageFromEditWindow(<?=$image->pid?>,'switchv')"></a>
            <?php endif;?>
            <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Upload new image');?>" class="switchiconimg right" onclick="hw.setNewImageFull(<?=$image->pid?>)"></a>
            <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Set as folder thumbnail');?>" class="foldericon right" onclick="hw.setFolderImage(<?=$image->pid?>)"></a>
            <a class="right moveimageicon" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Move image to another album')?>" onclick="hw.moveImageToAnotherAlbum(<?=$image->pid?>)" ></a>
        
            <h1><?=htmlspecialchars($image->name_user)?></h1>
        </div>
                        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_window.tpl.php'));?>
             
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_edit_block.tpl.php'));?>
            
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_details_block.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/picture_voting_block_admin.tpl.php'));?>
        
        <?php include_once(erLhcoreClassDesign::designtpl('lhgallery/image_comment_block_admin.tpl.php'));?>
        
    </div>
</div>