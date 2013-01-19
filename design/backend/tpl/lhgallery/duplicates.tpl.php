<?php if (isset($duplicates)) : ?>

<?php if (isset($pages)) : ?> 
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>
<div class="float-break">

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','Similar images number');?></th>
	<th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','Details');?></th>
	<th width="1%">&nbsp;</th>
</tr>
<? foreach ($duplicates as $key => $item) : ?>
    <tr>
        <td width="1%"><?=$item->id?></td>
       <td><?=$item->total_grouped?></td>
       <td><a onclick="hw.expandBlock(<?=$item->id?>)"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','More');?></a></td>
       <td><a href="<?=erLhcoreClassDesign::baseurl('gallery/deleteduplicatesession')?>/<?=$item->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','Delete duplicate session');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','Delete duplicate session');?>" /></a></td>
    </tr>  
    <tr class="duplicates-row" id="details-block-<?=$item->id?>" style="display:none;">
    	<td colspan="4">
    	 <?php foreach ($item->duplicate_images as $imageDuplicateItem) : 
    	 
    	 $imageDuplicate = $imageDuplicateItem->image;
    	 ?>
    	 		<div class="thumb-edit" id="image_thumb_<?=$imageDuplicate->pid?>">
			        <div class="right">
			        <a class="cursor" onclick="return hw.deletePhoto(<?=$imageDuplicate->pid?>)" ><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete image');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Delete image');?>" /></a>
			        </div>
			        <div class="thumb-pic">
			            <a href="<?=$imageDuplicate->url_path?>"><img title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','View image')?>" src="<?=erLhcoreClassDesign::imagePath($imageDuplicate->filepath.'thumb_'.urlencode($imageDuplicate->filename))?>" alt="<?=htmlspecialchars($imageDuplicate->name_user);?>" /></a>
			        </div>
			        <div class="thumb-attr">              
			           
			                <div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Title')?></div>				
							<input type="text" id="PhotoTitle_<?=$imageDuplicate->pid?>" value="<?=htmlspecialchars($imageDuplicate->title)?>" class="inputfield" />
			
							<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Keywords')?></div>	
							<input type="text" id="PhotoKeyword_<?=$imageDuplicate->pid?>" value="<?=htmlspecialchars($imageDuplicate->keywords)?>" class="inputfield" />	
							
							<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Caption')?></div>			
							<textarea class="default-textarea" id="PhotoDescription_<?=$imageDuplicate->pid?>"><?=htmlspecialchars($imageDuplicate->caption)?></textarea>	  
							<input type="button" onclick="hw.updatePhoto(<?=$imageDuplicate->pid?>)"class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/my_image_list','Update');?>" /><span class="status-img" id="image_status_<?=$imageDuplicate->pid?>"></span>H:<?=$imageDuplicate->hits?>,C:<?=date('Y-m-d H:i:s',$imageDuplicate->ctime)?>
							           
			        </div>
			    </div>
    	 <?php endforeach;?>
    	</td>
    </tr>
<?endforeach; ?>  
</table><br />  

</div>
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<?php else:?>

<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','No duplicates pending...');?>

<? endif;?>