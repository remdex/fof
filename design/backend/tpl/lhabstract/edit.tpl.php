<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','edit');?> - <?=$object_trans['name']?></h1>


<form enctype="multipart/form-data" action="<?=erLhcoreClassDesign::baseurl('/abstract/edit')?>/<?=$identifier?>/<?=$object->id?>" method="post">
	<?php include_once(erLhcoreClassDesign::designtpl('lhabstract/abstract_form.tpl.php'));?>		
</form>



