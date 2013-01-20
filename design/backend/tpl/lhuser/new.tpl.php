<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','New user');?></h1></div>

<div class="articlebody">

<? if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('user/new')?>" method="post" autocomplete="off">
		
		
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Name');?></label>
		<input class="inputfield" type="text" name="name" value="<?=htmlspecialchars($user->username);?>" />
		
		<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail');?></label>
		<input type="text" class="inputfield" name="Email" value="<?=$user->email;?>"/>
		
		<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Password');?></label>
		<input type="password" class="inputfield" name="Password" value=""/>
		
		<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Repeat password');?></label>
		<input type="password" class="inputfield" name="Password1" value=""/>
		
		<label>User group</label>
		<?php echo erLhcoreClassRenderHelper::renderCombobox( array (
	                        'input_name'     => 'DefaultGroup[]',	                 
	                        'selected_id'    => $user->user_groups_id,
	                        'css_class'      => 'inputfield w200',  
							'multiple' 		 => true,                     
	                        'list_function'  => 'erLhcoreClassModelGroup::getList'
	                )); ?>
	                
	     <?php /*           
	    <tr>
					<td>Send invite<br/> on registration</td>
					<td><input type="checkbox" name="SendInvite" value="on" <?php isset($sendinvite) ? print 'checked="checked"' : '' ?> /></td>
		</tr>
        */ ?>
				   
	   <label>Disabled&nbsp;<input type="checkbox" value="on" name="UserDisabled" <?php echo $user->disabled == 1 ? 'checked="checked"' : '' ?> /></label> 
	   
	   <input type="submit" class="small button" name="Update_account" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Save');?>"/>
	    	
		</form>
	</div>
	
</div>