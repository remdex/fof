<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Registration')?></h1>

<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php //include_once(erLhcoreClassDesign::designtpl('lhuser/open_id_block.tpl.php'));?>

<form method="post" action="<?=erLhcoreClassDesign::baseurl('user/registration')?>">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Username');?></label>
<input class="inputfield" type="text" name="Username" value="<?=htmlspecialchars($user->username);?>" />

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Password');?></label>
<input type="password" class="inputfield" name="Password" value=""/>

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Repeat password');?></label>
<input type="password" class="inputfield" name="Password1" value=""/>

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail');?></label>
<input type="text" class="inputfield" name="Email" value="<?=$user->email;?>"/>

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Safe code')?></label>
<input type="text" class="inputfield" name="CaptchaCode" value="" /><br />
<img src="<?=erLhcoreClassDesign::baseurl('captcha/image')?>/feedback_form" alt="">
<br />

<input type="submit" class="small button" name="Update_account" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Register')?>"/>

</form>