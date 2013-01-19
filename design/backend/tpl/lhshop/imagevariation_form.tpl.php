<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Name');?> *</label>
<input class="default-input" name="Name" type="text" value="<?=htmlspecialchars($imagevariation->name);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Applies to original');?></label>
<input type="checkbox" id="IDVariationType" value="on" name="VariationType" <?=$imagevariation->type == 1 ? 'checked="checked"' : ''?> />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Max width in pixels');?></label>
<input class="default-input size-input" name="Width" type="text" value="<?=htmlspecialchars($imagevariation->width);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Max height in pixels');?></label>
<input class="default-input size-input" name="Height" type="text" value="<?=htmlspecialchars($imagevariation->height);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Credits');?></label>
<input class="default-input" type="text" name="Credits" value="<?=htmlspecialchars($imagevariation->credits);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Position');?></label>
<input class="default-input" type="text" name="Position" value="<?=htmlspecialchars($imagevariation->position);?>" />
</div>

<script type="text/javascript">
$('#IDVariationType').click(function(){
	if ($(this).attr('checked'))
	{
		$('.size-input').attr("disabled","disabled");
	} else {$('.size-input').removeAttr("disabled");}
});
if ($('#IDVariationType').attr('checked'))
{
	$('.size-input').attr("disabled","disabled");
} else {$('.size-input').removeAttr("disabled");}
</script>
