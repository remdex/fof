<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Profile page settings');?></h1>
</div>

<? if (isset($account_updated) && $account_updated == 'done') : ?>
	<div class="dataupdate"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Profile settings updated');?></div>
<? endif; ?>

<form action="<?=erLhcoreClassDesign::baseurl('user/profilesettings')?>" method="post" enctype="multipart/form-data">
	<table width="100%">
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Name');?></td>
			<td><input class="inputfield" type="text" name="Name" value="<?=htmlspecialchars($profile->name);?>" /></td>
		</tr>
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Surname');?></td>
			<td><input type="text" class="inputfield" name="Surname" value="<?=htmlspecialchars($profile->surname);?>" /></td>
		</tr>
		<tr>
			<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Website');?></td>
			<td><input type="text" class="inputfield" name="Website" value="<?=htmlspecialchars($profile->website);?>" /></td>
		</tr>
		<tr>
			<td valign="top"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Information about you');?></td>
			<td>			
			<?php $bbcodeElementID = '#ProfileIntro';?>
            <?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>            
            <textarea name="Intro" rows="20" id="ProfileIntro" class="default-textarea big-textarea"><?=htmlspecialchars($profile->intro);?></textarea>            
			</td>
		</tr>
		<tr>
			<td valign="top"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Profile photo');?></td>
			<td>			
			<input type="file" name="qqfile" value="" />
            <?php if ($profile->has_photo) : ?>
            <div><br />
            <img src="<?=$profile->photow_60?>" alt="" /><br />
            <label><input type="checkbox" name="DeletePhoto" value="1" /> Delete</label>
            </div>
            <?php endif;?>        
			</td>
		</tr>									
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" class="default-button" name="Update_account" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Update');?>"/> <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','See how your profile looks to users')?>" href="<?=erLhcoreClassDesign::baseurl('user/profile')?>/<?=$profile->user_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','View profile')?></a></td>
		</tr>
	</table>		
</form>
