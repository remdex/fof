<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','User edit');?> - <? echo $user->username?></h1>

<?php if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<div class="explain"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Do not enter password unless you want to change it');?></div>
	<div><br />
		<form action="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$user->id?>" method="post">
		
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

	     <label>Disabled&nbsp;<input type="checkbox" value="on" name="UserDisabled" <?php echo $user->disabled == 1 ? 'checked="checked"' : '' ?> /></label>   

	          
	     <input type="submit" class="small button" name="Update_account" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Update');?>"/>
	     
	     
	       		
		</form>
	</div>
