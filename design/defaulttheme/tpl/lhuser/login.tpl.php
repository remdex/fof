<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Please login');?></h1>
</div>
<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

<?php //include_once(erLhcoreClassDesign::designtpl('lhuser/open_id_block.tpl.php'));?>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/login')?>">

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Username');?></label>
<input class="inputfield" type="text" name="email" value="" />

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password');?></label>
<input class="inputfield" type="password" name="pass" value="" />


<input class="small button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Login');?>" name="Login" />&nbsp;&nbsp;&nbsp;<a href="<?=erLhcoreClassDesign::baseurl('user/forgotpassword')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Password remind')?></a>
</form>