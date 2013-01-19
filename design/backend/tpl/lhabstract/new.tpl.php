
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','List');?> - <?=$object_trans['name']?></h1></div>

<div class="form-block">
	<div class="form-content">	
		<form method="post" enctype="multipart/form-data" action="<?=erLhcoreClassDesign::baseurl('abstract/new')?>/<?=$identifier?>">
		<?php include_once(erLhcoreClassDesign::designtpl('lhabstract/abstract_form.tpl.php'));?>
		</form>	
	</div>	
</div>
