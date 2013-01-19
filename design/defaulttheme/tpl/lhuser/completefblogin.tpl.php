<div class="row">
            <div class="eight columns">
            
	            <h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Login with facebook');?></h2>
	              
				<form method="post" action="">
				
					<div class="in-blk float-break">
					
						<?php if (isset($map_to_current)) : ?>
						<div class="left map-option">
						<strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Map to existing account');?></strong>
						<label><input type="radio" value="2" name="CreateAccount" checked /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','my account');?> (<?=htmlspecialchars($current_user->username)?>)</label>
						</div>
						<?php else : ?>
						<div class="left map-option">
						    <strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Login');?></strong>
						    <label><input type="radio" value="3" name="CreateAccount" <?=$create_account == 3 ? 'checked="checked"' : ''?>/> <strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Map to existing account');?></strong> </label>
						    <div class="map-login"<?php if (isset($map_to_current) && $create_account != 3) :?>style="display:none"<?php endif; ?>>
						        
						        <? if (isset($failed_authenticate)) : ?><br/><h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Incorrect login or password');?></h3><? endif;?>
						        
						        <div class="in-blk">
						        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','E-mail');?></label>
						        <input class="inputfield" type="text" name="Email" value="" />
						        </div>
						        
						        <div class="in-blk">
						        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Password');?></label>
						        <input class="inputfield" type="password" name="Password" value="" />
						        </div>
						    </div>
						
						</div>
						<?php endif;?>
						
						<div class="left map-option">
							<?php if (isset($multiple_action)) : ?>
							<strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','OR');?></strong>
							<label><input type="radio" value="1" name="CreateAccount" /> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Create an account');?></label>
							<br>					
							
							<? if (isset($user_email_taken)) : ?><br/><h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Provided e-mail is already taken');?></h3><? endif;?>
							
							
							<?php endif;?>
						</div>
					
					</div>
					
					<div class="float-break">
						<input type="submit" name="MapAccounts" class="large button" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/mapaccounts','Next');?>">
					</div>
								
				</form>
			
			</div>
			
			<div class="four columns">
                <div class="mpu">
                    <img src="http://placehold.it/300x250">
                </div>
                <div class="mpu">
                    <img src="http://placehold.it/300x250">
                </div>
            </div>
            
</div>

<script>
 var _lactq = _lactq || [];
_lactq.push({'f':'hw_init_map_account','a':[]});
</script>