<div class="row">
            <div class="eight columns">
            
			<h2 class="attr-h"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Login with Twitter');?></h2>
	
			<form method="post" action="">
			
				<div class="in-blk float-break">
				
					<?php if (isset($map_to_current)) : ?>
						<div class="left map-option">
							<strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Map to existing account');?></strong>					
							<label><input type="radio" value="2" name="CreateAccount" checked /><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','my account');?></label>
							<br />
							<br />
							(<?=htmlspecialchars($current_user->email)?>)
						</div>
					<?php else : ?>
						<div class="left map-option">
						    <strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Login');?></strong>
						    
						    <label><input type="radio" value="3" name="CreateAccount" <?=$create_account == 3 ? 'checked="checked"' : ''?>/> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Map to existing account');?></label>
						    
						    <div class="map-login"<?php if (isset($map_to_current) && $create_account != 3) :?>style="display:none"<?php endif; ?>>
		
						    	<br />
						    	
						        <? if (isset($failed_authenticate)) : ?>
						        	 <p class="error"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Incorrect login or password');?></p>
						        <? endif;?>			        
						       				
						        <div class="in-blk">
						        	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Username');?></label>
						        	<input class="inputfield" type="text" name="Username" value="" />
						        </div>
						        
						        <div class="in-blk">
						        	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Password');?></label>
						        	<input class="inputfield" type="password" name="Password" value="" />
						        </div>
						        
						    </div>
						    
						</div>
					<?php endif;?>
				
					<div class="left map-option pl20">
						<?php if (isset($multiple_action)) : ?>
						
							<strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','OR');?></strong>
							
							<label><input type="radio" value="1" name="CreateAccount" <?=$create_account == 1 ? 'checked="checked"' : ''?> /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','create account');?></label>
						
							<div class="map-login-create">
							
								<br />
								
							    <? if (isset($wrong_email)) : ?>
							    	
							    	<p class="error"><?=$wrong_email_msg;?></p>
							    <? endif;?>
							    
							    <div class="in-blk">
							        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','E-mail');?></label>
							      	<input class="inputfield" type="text" name="EmailCreat" title="E-mail" value="" />
							    </div>
							    
							</div>
						
						<?php endif;?>
					</div>
				
				</div>
				
				<div class="float-break">
					<input class="large button" type="submit" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Next');?>" name="MapAccounts" />
				</div>
			
			</form>
			
			
		</div>
</div>

<script>
$('input[name=CreateAccount]').change(function(){    
    if ($(this).val() == 3){
        $('.map-login').fadeIn();
        $('.map-login-create').fadeOut();
        $('input[name=Username]').focus();
    } else {
        $('.map-login').fadeOut();
        $('.map-login-create').fadeIn();
    }
});
</script>