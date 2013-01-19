<div class="right-infobox">
<?php
$currentUser = erLhcoreClassUser::instance();   
if ($currentUser->isLogged()) : 
$UserData = $currentUser->getUserData();
?>
	<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logged user');?></h3>
	<ul>
	   <li><a href="<?=erLhcoreClassDesign::baseurl('user/account')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>) &raquo;</a></li>
	   <li><a href="<?=erLhcoreClassDesign::baseurl('user/logout')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?> &raquo;</a></li> 	
	</ul>
	
<? 
unset($UserData);
else : ?>
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></h3>		
    	<ul>
	       <li><a href="<?=erLhcoreClassDesign::baseurl('user/login')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?> &raquo;</a>		   	
   </ul>
<?
endif;
unset($currentUser);
?>
</div>