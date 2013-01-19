<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

<table class="table-form">	 
<?php foreach ($object->getFields() as $fieldName => $attr) : ?>
   <tr>
		<td valign="top"><label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('abstract/main',$attr['trans']);?><?=$attr['required'] == true ? ' *' : ''?></label></td>
		<td colspan="3"><?php echo erLhcoreClassAbstract::renderInput($fieldName, $attr, $object)?></td>
	</tr>
<?endforeach;?>			
</table>

<input type="submit" class="default-button" name="SaveClient" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>&nbsp;	
<input type="submit" class="default-button" name="UpdateClient" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Update');?>"/>&nbsp;	
<input type="submit" class="default-button" name="CancelAction" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
