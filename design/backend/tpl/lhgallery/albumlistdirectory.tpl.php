

<?if (isset($writable) && $writable == false) : ?>
<p class="error"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','I cannot write this directory')?></p>
<?endif;?>

<?php if (count($filesList) > 0) : ?>
<table>
	<? foreach ($filesList as $file) : 
	if (!preg_match('/^(normal_|thumb_)/i',basename($file)) && !file_exists(dirname($file).'/thumb_'.str_replace(array('.swf','.avi','.mpg','.mpeg','.wmv','.ogv','.flv'),'.jpg',basename($file)))) :
	?>
	    <tr>
	        <td><?=$file?><img class="image_import" rel="<?=base64_encode($file);?>" src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>"/></td>
	    </tr>
	<?
	endif;
	endforeach;?>
	</table>
<?php else : ?>
	<span class="error"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/batchadd','No image were found!')?></span>
<?php endif;?>