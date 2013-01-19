<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Logged user');?> - <? echo $user->username?></h1>
</div>

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>

<? if (isset($account_updated) && $account_updated == 'done') : ?>
	<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Account updated');?></div>
<? endif; ?>


<div class="explain"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Do not enter password unless you want to change it');?></div>
	
<form action="<?=erLhcoreClassDesign::baseurl('user/account')?>" method="post">
	<table>		
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Name');?></td><td><input class="inputfield" type="text" name="name" value="<?=htmlspecialchars($user->username);?>" /></td>
		</tr>
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail');?></td>
			<td><input type="text" class="inputfield" name="Email" value="<?=$user->email;?>"/></td>
		</tr>	
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Password');?></td>
			<td><input type="password" class="inputfield" name="Password" value=""/></td>
		</tr>
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Repeat password');?></td>
			<td><input type="password" class="inputfield" name="Password1" value=""/></td>
		</tr>
											
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" class="default-button" name="Update" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Update');?>"/></td>
		</tr>
	</table>		
</form>

<?php //include_once(erLhcoreClassDesign::designtpl('lhuser/open_id_items.tpl.php'));?>