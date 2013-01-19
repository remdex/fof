<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','edit');?> - <?=$object_trans['name']?></h1></div>

<div class="articlebody">
	<div>
		<form enctype="multipart/form-data" action="<?=erLhcoreClassDesign::baseurl('/abstract/newspaperedit')?>/<?=$identifier?>/<?=$object->id?>" method="post">
			<?php include_once(erLhcoreClassDesign::designtpl('lhabstract/abstract_form.tpl.php'));?>		
		</form>
	</div>
</div>
<br />

