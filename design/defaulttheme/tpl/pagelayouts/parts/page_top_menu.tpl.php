<ul>
        <?php
        $currentUser = erLhcoreClassUser::instance();                       
        if ($currentUser->isLogged()) : 
        $UserData = $currentUser->getUserData(true);
        ?>                                       	
        	<li><a href="<?=erLhcoreClassDesign::baseurl('user/index')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>) 
        	<li><a href="<?=erLhcoreClassDesign::baseurl('user/logout')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?></a>                  
        <? 
        unset($UserData);                    
        else : ?>                                    	
        	<li><a href="<?=erLhcoreClassDesign::baseurl('user/login')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></a>                          
        	<li><a href="<?=erLhcoreClassDesign::baseurl('user/registration')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Register');?></a>                          
        <?
        endif;
        unset($currentUser);
        ?>
        
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhforum','use_anonymous')) : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('forum/index')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Forum');?></a> 
		<?php endif;?>		
</ul>