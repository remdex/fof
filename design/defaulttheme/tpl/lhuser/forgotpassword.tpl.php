<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Password remind');?></h1>


<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/forgotpassword')?>">

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','E-mail')?>:</label>
<input type="text" class="inputfield" name="Email" value="" />

<input type="submit" class="small button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Restore password')?>" name="Forgotpassword" />

</form>