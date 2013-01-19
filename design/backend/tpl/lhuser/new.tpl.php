<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','New user');?></h1></div>


<div class="articlebody">

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>

	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('user/new')?>" method="post" autocomplete="off">
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
					<td valign="top">Default group</td>
					<td>
					<?php echo erLhcoreClassRenderHelper::renderCombobox( array (
	                        'input_name'     => 'DefaultGroup[]',	                 
	                        'selected_id'    => $user->user_groups_id,
	                        'css_class'      => 'inputfield w200',  
							'multiple' 		 => true,                     
	                        'list_function'  => 'erLhcoreClassModelGroup::getList'
	                )); ?>
					</td>
				</tr>				
				<tr>
					<td>Send invite<br/> on registration</td>
					<td><input type="checkbox" name="SendInvite" value="on" <?php isset($sendinvite) ? print 'checked="checked"' : '' ?> /></td>
				</tr>
				<tr>
					<td>Disabled</td>
					<td><input type="checkbox" value="on" name="UserDisabled" <?php echo $user->disabled == 1 ? 'checked="checked"' : '' ?> /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" class="default-button" name="Update_account" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Save');?>"/></td>
				</tr>
			</table>	
			
		</form>
	</div>
	
</div>