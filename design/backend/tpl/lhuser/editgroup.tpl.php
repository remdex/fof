<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Group edit');?> - <?=$group->name?></h1></div>

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>

	<div>
		<form action="<?=erLhcoreClassDesign::baseurl('user/editgroup')?>/<?=$group->id?>" method="post">
			<table>
				<tr>
					<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Title');?></td><td><input class="inputfield" type="text" name="Name"  value="<?=htmlspecialchars($group->name);?>" /></td>
				</tr>									
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="default-button" name="Update_group" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Update');?>"/></td>
				</tr>
			</table>		
		</form>
	</div>

	<br />


<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Assigned users');?> - <?=$group->name?></h1></div>
<form action="<?=erLhcoreClassDesign::baseurl('user/editgroup')?>/<?=$group->id?>" method="post">

<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
    <th>&nbsp;</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Username');?></th>
</tr>
<? foreach ($users as $UserAssigned) : ?>
<tr>
    <td><input type="checkbox" name="AssignedID[]" value="<?=$UserAssigned->id?>" /></td>
    <td><?=$UserAssigned->user?></td>
</tr>
<? endforeach;?>

</table>
<br />
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<div>
<br />
<input type="submit" class="default-button" name="Remove_user_from_group" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Remove user from group');?>" /> <input class="default-button" type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Assign user');?>" onclick="lhinst.abstractDialogFrame('assign-user-dialog','<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','User assignment');?>','<?=erLhcoreClassDesign::baseurl('user/groupassignuser')?>/<?=$group->id?>')" />
</div>
</form>

<br />

<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Assigned roles');?> - <?=$group->name?></h1></div>
<form action="<?=erLhcoreClassDesign::baseurl('user/editgroup')?>/<?=$group->id?>" method="post">

<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
    <th>&nbsp;</th>
    <th>Name</th>
</tr>
<? foreach (erLhcoreClassGroupRole::getGroupRoles($group->id) as $UserAssigned) : ?>
<tr>
    <td><input type="checkbox" name="AssignedID[]" value="<?=$UserAssigned['assigned_id']?>" /></td>
    <td><?=$UserAssigned['name']?></td>
</tr>
<? endforeach; ?>

</table>
<div>
<br />
<input type="submit" class="default-button" name="Remove_role_from_group" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Remove role from group');?>" /> <input class="default-button" type="button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Assign role');?>" onclick="lhinst.abstractDialog('assign-role-dialog','<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','Role assignment');?>','<?=erLhcoreClassDesign::baseurl('permission/groupassignrole')?>/<?=$group->id?>')" />
</div>
</form>
</fieldset>



<div id="assign-user-dialog"></div>
<div id="assign-role-dialog"></div>

<? if (isset($adduser)) : ?>
<script type="text/javascript">
$(function() {
    lhinst.abstractDialog('assign-user-dialog','<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/editgroup','User assignment');?>','<?=erLhcoreClassDesign::baseurl('user/groupassignuser')?>/<?=$group->id?>');
})
</script>
<? endif; ?>