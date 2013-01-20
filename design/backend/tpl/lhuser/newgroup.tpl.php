<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','New group');?></h1>

<? if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

<form action="<?=erLhcoreClassDesign::baseurl('user/newgroup')?>" method="post">
	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','Title');?></label>
	<input class="inputfield" type="text" name="Name"  value="" />
	
	
	<input type="submit" class="small button" name="Save_group" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','Save');?>"/> <input type="submit" class="small button" name="Save_group_and_assign_user" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/newgroup','Save and assign user');?>"/></td>
			
</form>
	

