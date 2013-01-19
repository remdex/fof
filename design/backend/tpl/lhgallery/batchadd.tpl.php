<? if (count($directoryList) > 0) :?>
<ul class="directory-input-list">
<?php 
foreach ($directoryList  as $key => $directory) : ?>
<li class="dir-item" id="directory_id<?=$key?>">
<a id="directoryListLink<?=$key?>" rel="<?=urlencode(base64_encode($directory));?>" href="<?=erLhcoreClassDesign::baseurl('gallery/batchadd')?>/(directory)/<?=urlencode(base64_encode($directory));?>"><?=$directory?></a> | <a href="<?=erLhcoreClassDesign::baseurl('/gallery/batchadd')?>/(directory)/<?=urlencode(base64_encode($directory));?>/(import)/1"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Import')?></a> | <a href="<?=erLhcoreClassDesign::baseurl('gallery/batchadd')?>/(directory)/<?=urlencode(base64_encode($directory));?>/(importrecur)/1"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Import recursive this directory')?></a>

<div class="album-search-row"><input type="text" rel="<?=$key?>" class="AlbumNameInput default-input" value="" />&nbsp;<input class="default-button" type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Search')?>" /></div>
<br />
<div id="album_select_directory<?=$key?>">
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Please enter album name and click search')?>
</div>
<br />
<input type="button" rel="<?=$key?>" class="listImagesButton default-button" name="ListImages" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','List images')?>" />&nbsp;<input type="button" rel="<?=$key?>" class="listRecursiveImagesButton default-button" <?=erLhcoreClassGalleryBatch::hasSubdir($directory) ? '' : 'disabled="disabled"'?> name="ListImagesRecursive" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','List images recursive')?>" />&nbsp;<input type="button" id="ImportDirectoryButton<?=$key?>" rel="<?=$key?>" class="importButtonDirectory default-button" name="ImportImages" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Import images')?>" disabled="disabled" />
<br />
<br />

<div id="import-images<?=$key?>">
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Click list images or list images recursive')?>
</div>
<br />

</li>
<?endforeach;?>
</ul>
<br />
<?endif;?>

<?if (isset($writable) && $writable == false) : ?>
<p class="error"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','I cannot write this directory')?></p>
<?endif;?>

<? if (isset($filesList)) : ?>

<select id="AlbumID"><? 
$previousCategory='';
foreach (erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 5000,'offset' => 0,'sort' =>'category ASC')) as $album): ?>
    <?if ($previousCategory != $album->category): ?>
        <optgroup label="<? $previousCategory = $album->category;$pathReduced = $album->path_album;array_pop($pathReduced); foreach ($pathReduced as $pathItem){ echo $pathItem['title'].'/'; } ?>">
    <?endif;?>
    <option value="<?=$album->aid?>"><?=$album->title?></option>
    <?if ($previousCategory != $album->category): ?>
    </optgroup>
    <?endif;?>    
<?endforeach;?>
</select>


<table>
<? foreach ($filesList as $file) : 
if (!preg_match('/^(normal_|thumb_)/i',basename($file))) :
?>
    <tr>
        <td><?=$file?><img class="image_import" rel="<?=base64_encode($file);?>" src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>"/></td>
    </tr>
<?
endif;
endforeach;?>
</table>

<?if (isset($writable) && $writable == true) : ?>
<input type="button" class="default-button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Add images')?>" onclick="startImport()" />
<? else : ?>
<input type="button" class="default-button" disabled="disabled" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Add images')?>" />
<? endif;?>


<?endif;?>


<div id="status"></div>

<script type="text/javascript">

$('.AlbumNameInput').change(function(){	
	var albumInputInstanceDirecoty = $(this).attr('rel');
	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumnamesuggest')?>/"+albumInputInstanceDirecoty+"/"+escape($(this).val()), {} , function(data){	
                   $('#album_select_directory'+albumInputInstanceDirecoty).html(data.result);
    	});	
});

$('.listImagesButton').click(function(){	
	var albumInputInstanceDirecoty = $(this).attr('rel');
	$.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/albumlistdirectory')?>/"+$('#directoryListLink'+albumInputInstanceDirecoty).attr('rel'), {} , function(data){	
                   $('#import-images'+albumInputInstanceDirecoty).html(data.result);
                   if (data.is_writable == true) {                   		
                   	 	$('#ImportDirectoryButton'+albumInputInstanceDirecoty).removeAttr("disabled");
                   } else {
                   	 	$('#ImportDirectoryButton'+albumInputInstanceDirecoty).attr("disabled");
                   }
    	});	
});

$('.listRecursiveImagesButton').click(function(){	
	var albumInputInstanceDirecoty = $(this).attr('rel');
	$.getJSON("<?=erLhcoreClassDesign::baseurl('/gallery/albumlistdirectory')?>/"+$('#directoryListLink'+albumInputInstanceDirecoty).attr('rel')+'/true', {} , function(data){	
                   $('#import-images'+albumInputInstanceDirecoty).html(data.result);
                   if (data.is_writable == true) {                   		
                   	 	$('#ImportDirectoryButton'+albumInputInstanceDirecoty).removeAttr("disabled");
                   } else {
                   		
                   	 	$('#ImportDirectoryButton'+albumInputInstanceDirecoty).attr("disabled","disabled");
                   }
    	});	
});

$('.importButtonDirectory').click(function(){
	var currentDirectoryImportID = $(this).attr('rel');
	if ($('input[name=AlbumDestinationDirectory'+$(this).attr('rel')+']:checked').val() != undefined){
		var currentImportAlbumID = $('input[name=AlbumDestinationDirectory'+$(this).attr('rel')+']:checked').val();
		startImportQuick(currentDirectoryImportID,currentImportAlbumID);
	} else {
		alert("<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','Please choose album first!')?>");
	}
});

function startImportQuick(currentDirectoryImportID,currentImportAlbumID)
{
	if ($('#import-images'+currentDirectoryImportID+' .image_import').eq(0).attr('rel') != undefined)
    {    	    	
        $.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/addimagesbatch')?>/"+currentImportAlbumID+"/(image)/"+$('#import-images'+currentDirectoryImportID+' .image_import').eq(0).attr('rel'), {} , function(data){	
              $('#import-images'+currentDirectoryImportID+' .image_import').eq(0).attr('src','<?=erLhcoreClassDesign::design('images/icons/accept.png')?>');
              $('#import-images'+currentDirectoryImportID+' .image_import').eq(0).removeClass('image_import');	
    		   startImportQuick(currentDirectoryImportID,currentImportAlbumID);        
    	});    	
    }
}

function startImport()
{
    if ($('.image_import').eq(0).attr('rel') != undefined)
    {
        $.getJSON("<?=erLhcoreClassDesign::baseurl('gallery/addimagesbatch')?>/"+$('#AlbumID').val()+"/(image)/"+$('.image_import').eq(0).attr('rel'), {} , function(data){	
              $('.image_import').eq(0).attr('src','<?=erLhcoreClassDesign::design('images/icons/accept.png')?>');
              $('.image_import').eq(0).removeClass('image_import');	
    		   startImport();        
    	});    	
    }  

}
</script>