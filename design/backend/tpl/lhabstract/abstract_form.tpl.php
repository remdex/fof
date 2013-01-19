<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

 
<?php foreach ($object->getFields() as $fieldName => $attr) : ?>
 <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('abstract/main',$attr['trans']);?><?=$attr['required'] == true ? ' *' : ''?></label>
<?php echo erLhcoreClassAbstract::renderInput($fieldName, $attr, $object)?>
<?endforeach;?>			


<br />


<input type="submit" class="small button" name="SaveClient" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Save');?>"/>&nbsp;	
<input type="submit" class="small button" name="UpdateClient" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Update');?>"/>&nbsp;	
<input type="submit" class="small button" name="CancelAction" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/buttons','Cancel');?>"/>
