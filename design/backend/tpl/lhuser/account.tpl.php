<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Logged user');?> - <? echo $user->username?></h1>
</div>

<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

<? if (isset($account_updated) && $account_updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Account updated'); ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>	
<? endif; ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Do not enter password unless you want to change it');?></p>
	
<form action="<?=erLhcoreClassDesign::baseurl('user/account')?>" method="post">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Name');?></label>
<input class="inputfield" type="text" name="name" value="<?=htmlspecialchars($user->username);?>" />

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail');?></label>
<input type="text" class="inputfield" name="Email" value="<?=$user->email;?>"/>


<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Password');?></label>
<input type="password" class="inputfield" autocomplete="off" name="Password" value=""/>

<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Repeat password');?></label>
<input type="password" class="inputfield" autocomplete="off" name="Password1" value=""/>
											
<input type="submit" class="small button" name="Update" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Update');?>"/>
</form>

<?php //include_once(erLhcoreClassDesign::designtpl('lhuser/open_id_items.tpl.php'));?>