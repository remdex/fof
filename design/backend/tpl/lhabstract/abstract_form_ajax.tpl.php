<div id="error-list"></div>
<table class="table-form">	 
<?php foreach ($object->getFields() as $fieldName => $attr) : ?>
   <tr>
		<td><label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('abstract/main',$attr['trans']);?><?=$attr['required'] == true ? ' *' : ''?></label></td>
		<td colspan="3"><?php echo erLhcoreClassAbstract::renderInput($fieldName, $attr, $object)?></td>
	</tr>
<?endforeach;?>			
</table>

<input type="button" class="update-button" onclick="lhinst.saveAbstractAjax('<?=$identifier?>')" name="SaveClient" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>&nbsp;	
<input type="button" onclick="$('#assign-user-dialog').dialog('destroy')" class="cancel-button" name="CancelAction" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
