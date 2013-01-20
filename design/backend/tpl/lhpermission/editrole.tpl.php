<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Role edit');?> - <?=$role->name?></h1>

<div class="articlebody">

<? if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('/permission/editrole')?>/<?=$role->id?>" method="post">
		      <div class="row">
		          <div class="columns two">
		              <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Title');?></label>
		          </div>
		          <div class="columns two end">
		              <label><input class="inputfield" type="text" name="Name"  value="<?=htmlspecialchars($role->name);?>" /></label>
		          </div>		      
		      </div>
			
			<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Assigned functions');?></legend> 			
			<table class="lentele" cellpadding="0" cellspacing="0">
			<thead>
			<tr>
			     <th>&nbsp;</th>
			     <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Module');?></th>
			     <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Function');?></th>	
			</tr>
			</thead>
			     <? foreach (erLhcoreClassRoleFunction::getRoleFunctions($role->id) as $Function) : ?>
			     <tr>			     
    			     <td><input type="checkbox" name="PolicyID[]" value="<?=$Function['id']?>" /></td>
    			     <td><?=$Function['module']?></td>  
    			     <td><?=$Function['function']?></td>  	
			     </tr>			     
			     <? endforeach; ?>	
			</table>			
			<input type="submit" class="small button" name="Delete_policy" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Remove selected');?>"/>
			<input type="submit" class="small button" name="New_policy" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','New policy');?>"/>		<br /><br />

	
			</fieldset>
		
			<input type="submit" class="small button" name="Update_role" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Update');?>"/>	
			<input type="submit" class="small button" name="Cancel_role" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Cancel');?>"/>	<br />


		</form>
	</div>
</div>


<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Role assigned groups');?></legend>
	<div>
		<form action="<?=erLhcoreClassDesign::baseurl('/permission/editrole')?>/<?=$role->id?>" method="post">			
			<table class="lentele" cellpadding="0" cellspacing="0">
			<thead>
			<tr>
			     <th width="1%">&nbsp;</th>
			     <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Title');?></th>	
			</tr>
			</thead>
			     <? foreach (erLhcoreClassGroupRole::getRoleGroups($role->id) as $Group) : ?>
			     <tr>			     
    			     <td><input type="checkbox" name="AssignedID[]" value="<?=$Group['assigned_id']?>" /></td>
    			     <td><?=$Group['name']?></td>      		
			     </tr>			     
			     <? endforeach; ?>	
			</table>	
			

			<input type="submit" class="small button" name="Remove_group_from_role" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Remove selected');?>"/>	
			<input type="button" class="small button" onclick="lhinst.abstractDialog('assign-group-dialog','<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Group assignement to role');?>','<?=erLhcoreClassDesign::baseurl('permission/roleassigngroup')?>/<?=$role->id?>')" name="Assign_group_role" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/editrole','Assign group');?>"/>	
		</form>
	</div>
</fieldset>

<div id="assign-group-dialog"></div>