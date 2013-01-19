<div class="row">
            <div class="eight columns">
           
           <h2>Associated logins</h2>
           
           <?php include_once(erLhcoreClassDesign::designtpl('lhuser/open_id_items.tpl.php'));?>
					<br>
			<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'facebook', 'enabled' ) == true) : ?>			
			    <?php include_once(erLhcoreClassDesign::designtpl('lhuser/facebook_items.tpl.php'));?>			
			<?php endif;?>
           
           <?php if (erConfigClassLhConfig::getInstance()->getSetting( 'twitter', 'enabled' ) == true) : ?>
				<?php include_once(erLhcoreClassDesign::designtpl('lhuser/twitter_items.tpl.php'));?>
		   <?php endif;?>
           
           <a href="/user/index" class="large button">Return</a>
           
           </div>
</div>