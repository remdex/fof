<?php $bbcodeElementID = '#PhotoDescription_'.$image->pid;?>
<?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>

<div class="picture-details">
    <div class="sub-header">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Image edit')?></h3>
    </div>
    
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Title')?></div>				
    <input type="text" id="PhotoTitle_<?=$image->pid?>" value="<?=htmlspecialchars($image->title)?>" class="inputfield" />
    
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Keywords')?></div>	
    <input type="text" id="PhotoKeyword_<?=$image->pid?>" value="<?=htmlspecialchars($image->keywords)?>" class="inputfield" />	
    
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Anaglyph')?></div>	
    <input type="checkbox" id="PhotoAnaglyph_<?=$image->pid?>" <?=$image->anaglyph == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
    
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Approved')?></div>	
    <input type="checkbox" id="PhotoApproved_<?=$image->pid?>" <?=$image->approved == 1 ? 'checked="checked"' : ''?> class="inputcheckbox" />	
    			
    <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Caption')?></div>			
    <textarea class="default-textarea" id="PhotoDescription_<?=$image->pid?>"><?=htmlspecialchars($image->caption)?></textarea>	
    <br />

    <input type="button" onclick="hw.updatePhoto(<?=$image->pid?>)"class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Update');?>" /><span class="status-img" id="image_status_<?=$image->pid?>"></span>             
    
</div>

 