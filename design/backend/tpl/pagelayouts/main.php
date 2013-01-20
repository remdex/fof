<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
</head>
<body>
     
       
<div class="row">
    <div class="columns twelve">

   <h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->getSetting( 'site', 'title' )?></a></h1>

	<ul class="nav-bar">
		<?php $currentUser = erLhcoreClassUser::instance();  ?>
	            	    
	    <?php if ( erLhcoreClassUser::instance()->hasAccessTo('lharticle','edit') ) : ?>
	    <li><a href="<?=erLhcoreClassDesign::baseurl('article/staticlist')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Static articles');?></a></li>
	    <li><a href="<?=erLhcoreClassDesign::baseurl('article/managecategories')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Articles categories');?></a></li>
	    <?php endif;?>
	    
	    <li><a href="<?=erLhcoreClassDesign::baseurl('abstract/index')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Parameters');?></a></li>
	          	            	    
	    <?php if ( erLhcoreClassUser::instance()->hasAccessTo('lhsystem','use') ) : ?>
	    <li><a href="<?=erLhcoreClassDesign::baseurl('system/configuration')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Configuration');?></a></li>
	    <?php endif;?>
	            					
		<?php if ($currentUser->isLogged()) : 
		$UserData = $currentUser->getUserData(); ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('user/account')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>)</a></li>
		<li><a href="<?=erLhcoreClassDesign::baseurl('user/logout')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?></a></li> 	
		<? unset($UserData); else : ?>
		<li><a href="<?=erLhcoreClassDesign::baseurl('user/login')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></a>
		<? endif;unset($currentUser); ?>
	</ul>       
     
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>
        
    <div class="row">
        <div class="columns two">         
    	       <?php if (isset($Result['left_menu'])) : ?>
    	          <?php switch ($Result['left_menu']) {
    	          	case 'forum': ?>
    	          	       <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_forum.tpl.php'));?>	
    	          		<?php break;?>
    	          		
    	          	<?php case 'abstract': ?>
    	          		 <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/sidemenu/abstract.tpl.php')); ?>
    	          	<?php break; ?>
    
    	          	<?php default:
    	          		break;
    	          
    	          }
    	      else : ?>
    	      	<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhsystem','use_newspaper')) : ?>
    	          <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_admin.tpl.php'));?>	
    	        <?php endif;?>  
    	      <?php endif;?>    	
        </div>
    
        <div class="columns eight">
        <?
		     echo $Result['content'];		
		?>	
        </div> 
        
        <div class="columns two"> 
            <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/user_box.tpl.php'));?>	
			    				
			<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/clear_cache_box.tpl.php'));?>	
        </div>
        
    </div>
    
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>

    </div>
</div>

</body>
</html>