<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','List');?> - <?=$object_trans['name']?></legend>
<div class="form-block">
	<div class="form-content">	
		<form method="post" action="<?=erLhcoreClassDesign::baseurl('/abstract/new/'.$identifier)?>">
		<?php include_once(erLhcoreClassDesign::designtpl('lhabstract/abstract_form_ajax.tpl.php'));?>
		</form>	
	</div>	
</div>
</fieldset>