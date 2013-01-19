<div class="row">
            <div class="eight columns">
           
           <h2>User account</h2>
            
<? if (isset($errors)) : ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<? endif; ?>

<? if (isset($account_updated) && $account_updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Account updated'); ?>
	<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>	
<? endif; ?>


<div class="explain"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Do not enter password unless you want to change it');?></div>
<br />

<form action="<?=erLhcoreClassDesign::baseurl('user/account')?>" method="post" autocomplete="off">

		
	<div class="row">
		<div class="three mobile-one columns">
			<label class="inline" for="name">Name:</label>
		</div>
		<div class="nine mobile-three columns">
			<input type="text" value="<?=htmlspecialchars($user->username);?>" name="name" placeholder="Your name" id="name" class="required">
		</div>
	</div>
	
	<div class="row">
		<div class="three mobile-one columns">
			<label class="inline" for="email">Email:</label>
		</div>
		<div class="nine mobile-three columns">
			<input type="text" value="<?=$user->email;?>" name="Email" placeholder="Your email address" id="email" class="required email valid">
		</div>
	</div>
	
	<div class="row">
		<div class="three mobile-one columns">
			<label class="inline" for="email">Password:</label>
		</div>
		<div class="nine mobile-three columns">
			<input type="password" class="inputfield" placeholder="Enter new password" name="Password" value=""/>
		</div>
	</div>
	
	<div class="row">
		<div class="three mobile-one columns">
			<label class="inline" for="email">Repeat password:</label>
		</div>
		<div class="nine mobile-three columns">
			<input type="password" class="inputfield" placeholder="Repeat new password" name="Password1" value=""/>
		</div>
	</div>
					
	<div class="row">
          <div class="nine offset-by-three columns">
	            <input type="submit" name="Update" class="small button" value="Update">&nbsp;<a href="/user/index" class="small button">Return</a>
          </div>
    </div>	
			
			
</form>

</div>
</div>